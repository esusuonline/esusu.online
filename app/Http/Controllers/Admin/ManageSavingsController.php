<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\SavingsProcess;
use App\Models\TimeInterval;
use App\Models\GeneralSetting;
use App\Models\PaidLog;
use App\Models\Savings;
use App\Models\SavingsPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageSavingsController extends Controller
{

    protected $pageTitle    = 'All Savings';
    protected $emptyMessage = 'No savings found';
    protected $view         = 'admin.savings.index';
    protected $savingsList        = null;

    public function allSavings()
    {
        $data  = $this->filterSavings();
        return view($this->view, $data);
    }

    public function pendingMaturedSavings()
    {
        $data  = $this->filterSavings('pendingMatured');
        return view($this->view, $data);
    }
    public function paidMaturedSavings()
    {
        $data  = $this->filterSavings('paidMatured');
        return view($this->view, $data);
    }

    public function activeSavings()
    {
        $data  = $this->filterSavings('active');
        return view($this->view, $data);
    }

    public function pendingSavings()
    {
        $data  = $this->filterSavings('pending');
        return view($this->view, $data);
    }

    protected function filterSavings($scope = null)
    {
        $savingsList = Savings::query();

        if($scope){
            $savingsList        = Savings::$scope();
            $this->pageTitle    = ucfirst($scope) . ' Savings';
            $this->emptyMessage = "No $scope savings found";
        }

        $searchKey = request()->search;

        if($searchKey){
            $savingsList = $savingsList->where(function($query) use($searchKey){
                $query->whereHas('user', function($user) use($searchKey){
                    $user->where('username', 'LIKE', "%$searchKey%");
                })->orWhereHas('savingsPlan', function($savingsPlan) use($searchKey){
                    $savingsPlan->where('name', 'LIKE', "%$searchKey%");
                });
            });
        }

        $savingsList = $savingsList->with(['user', 'savingsPlan', 'paidLogs'=>function($paidLog){
            $paidLog->where('status', 1);
        }]);

        $savingsList = $savingsList->latest()->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        $data['savingsList'] = $savingsList;
        $data['days'] = $days;
        $data['pageTitle'] = $this->pageTitle;
        $data['emptyMessage'] = $this->emptyMessage;

        return $data;

    }

    public function maturedTransfer(Request $request){
        $request->validate([
            'savings_id' => 'required',
        ]);

        $savings = Savings::where('status', 2)->where('transfer_user',0)->findOrFail($request->savings_id);
        $savings->transfer_user = 1;
        $savings->save();

        $general = GeneralSetting::first();
        notify($savings->user, 'MATURED_PAID', [
            'savings_plan'         => $savings->savingsPlan->name,
            'savings_amount'       => showAmount($savings->savings_amount),
            'giveable_amount'      => showAmount($savings->giveable_amount),
            'currency'             => $general->cur_text,
        ]);

        $notify[] = ['success', 'Savings balance successfully transfer to user'];
        return back()->withNotify($notify);
    }

    public function showSavingsForm(Request $request){
        $pageTitle = 'Register New Savings';
        $plans = SavingsPlan::where('status', 1)->get();
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('admin.savings.register', compact('pageTitle', 'plans', 'days'));
    }

    public function saveSavings(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'plan_id'  => 'required'
        ],[
            'user.required' => 'Username or email field required'
        ]);

        $user = User::where('email', $request->user)->orWhere('username', $request->user)->firstOrFail();
        $plan = SavingsPlan::findOrFail($request->plan_id);

        $savings                       = new Savings();
        $savings->user_id              = $user->id;
        $savings->savings_plan_id      = $plan->id;
        $savings->savings_amount       = $plan->savings_amount;
        $savings->giveable_amount      = $plan->giveable_amount;
        $savings->installment          = $plan->installment;
        $savings->installment_interval = $plan->installment_interval;
        $savings->total_installment    = $plan->total_installment;
        $savings->late_fee             = $plan->fixed_late_fee + ($plan->installment * $plan->percent_late_fee / 100);
        $savings->save();

        $general = GeneralSetting::first();
        notify($savings->user, 'SAVINGS_REGISTER', [
            'savings_plan'         => $savings->savingsPlan->name,
            'amount'               => showAmount($savings->savings_amount),
            'currency'             => $general->cur_text,
            'installment'          => showAmount($savings->installment),
            'total_installment'    => $savings->total_installment,
            'installment_interval' => $savings->installment_interval.' days'
        ]);

        $notify[] = ['success', 'Savings member added successfully'];
        return back()->withNotify($notify);
    }

    public function pendingDetails($id)
    {
        $pageTitle = 'Pending Savings Details';
        $savings   = Savings::with('savingsPlan')->findOrFail($id);
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('admin.savings.pending_details', compact('pageTitle', 'savings', 'days'));
    }


    public function approveSavings(Request $request)
    {
        $savings = Savings::with('user', 'savingsPlan')->findOrFail($request->savings_id);
        $duration = $savings->total_installment * $savings->installment_interval;
        $savings_end = date('Y-m-d', strtotime(' +'.$duration.' days')); 
        $savings_start = date('Y-m-d'); 
        
        $savings->next_installment = now();
        $savings->next_payment = date('Y-m-d');
        $savings->status = 1;
        $savings->savings_end = $savings_end;
        $savings->savings_start = $savings_start;
        $savings->save();

        $general = GeneralSetting::first();
        notify($savings->user, 'SAVINGS_REGISTER', [
            'savings_plan'         => $savings->savingsPlan->name,
            'amount'               => showAmount($savings->savings_amount),
            'currency'             => $general->cur_text,
            'installment'          => showAmount($savings->installment),
            'total_installment'    => $savings->total_installment,
            'installment_interval' => $savings->installment_interval.' days'
        ]);

        $notify[] = ['success', 'Savings approved successfully'];
        return back()->withNotify($notify);
    }

    public function installment(Request $request)
    {
        $savings = Savings::with(['user', 'savingsPlan', 'paidLogs'=>function($paidLog){
            $paidLog->where('status', 1);
        }])->findOrFail($request->savings_id);

        $user = $savings->user;
        $savingsProcess = new SavingsProcess([
            'user'=>$user,
        ]);

        $response = $savingsProcess->installment($savings);
        if ($response['error'] == true) {
            return back()->withNotify($response['message']);
        }

        $paidLog               = new PaidLog();
        $paidLog->savings_id   = $request->savings_id;
        $paidLog->user_id      = $savings->user->id;
        $paidLog->staff_id      = $savings->user->agent_id;
        $paidLog->amount       = $savingsProcess->installment;
        $paidLog->late_fee     = $savingsProcess->lateFee;
        $paidLog->final_amount = $savingsProcess->amountWithLateFee;
        $paidLog->status       = 1;
        $paidLog->save();

        $savingsProcess->updateSavings($savings);

        $general = GeneralSetting::first();
        notify($savings->user, 'SAVINGS_PAID', [
            'amount' => showAmount($savingsProcess->amountWithLateFee),
            'currency' => $general->cur_text,
            'paid_by' => 'Admin',
            'savings_plan' => $savings->savingsPlan->name
        ]);

        $notify[] = ['success', "Installment taken successfully"];
        return back()->withNotify($notify);
    }

    public function close(Request $request){
        $savings = Savings::where('status', 1)->findOrFail($request->savings_id);
        $savings->status = 3;
        $savings->save();

        $notify[] = ['success', 'Savings close successfully'];
        return back()->withNotify($notify);
    }

    public function userSavings($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = $user->fullname.' active savings';
        $emptyMessage = 'No active savings found';
        $savingsList = Savings::active()->where('user_id', $id)->with(['user', 'savingsPlan', 'paidLogs'=>function($paidLogs){
            $paidLogs->where('status', 1);
        }])->paginate(getPaginate(20));
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('admin.savings.index', compact('pageTitle', 'emptyMessage', 'savingsList', 'days'));
    }
    
    // MINE
    public function userInstallment(Request $request){
        $savings = DB::table('savings')->where('id', $request->savings_id)->first();
        if(Auth::guard('admin')->user()->withdrawable_funds >= $savings->installment){
            
            // USER SAVINGS UPDATE
           DB::table('savings')->where('id', $request->savings_id)->update([
               'total_paid' => $savings->total_paid + $savings->installment, 
               'installment_given' => $savings->installment_given + 1,
            ]);

            if($savings->total_installment == ($savings->installment_given + 1)){
                // SAVINGS STATUS UPDATE
                DB::table('savings')->where('id', $request->savings_id)->update([
                   'status' => 2,
                   'last_payment' => date('Y-m-d'),
                ]);
                
                // ADMIN BALANCE UPPDATE
                DB::table('admins')->where('id', Auth::guard('admin')->user()->id)->update([
                    'total_assets' => DB::raw('total_assets+'.$savings->savings_amount),
                    'active_savings_balance' => DB::raw('active_savings_balance-'.$savings->total_paid),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }else{
                DB::table('savings')->where('id', $request->savings_id)->update([
                   'last_payment' => date('Y-m-d'),
                   'next_payment' => date('Y-m-d', strtotime(' +'.$savings->installment_interval.' days')),
                ]);
                
                DB::table('admins')->where('id', Auth::guard('admin')->user()->id)->update([
                    'active_savings_balance' => DB::raw('active_savings_balance+'.$savings->installment),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }
            // $savings_end = date('d-m-Y', strtotime(' +'.$duration.' days')); 
            
            PaidLog::create([
                'user_id' => $savings->user_id,
                'savings_id' => $savings->id,
                'savings_plan_id' => $savings->savings_plan_id,
                'staff_id' => $savings->staff_id,
                'admin_pay' => Auth::guard('admin')->user()->id,
                'amount' => $savings->installment,
                'final_amount' => $savings->installment,
                'status' => 1,
            ]);
            $notify[] = ['success', 'Installment taken successfully'];
            
            return redirect()->back()->withNotify($notify);
        }else{
            $notify[] = ['error', 'Insufficient Funds'];
            
            return redirect()->back()->withNotify($notify);
        }   
    }
    
}
