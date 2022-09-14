<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Lib\SavingsProcess;
use App\Lib\LoanProcess;
use App\Models\TimeInterval;
use App\Models\GeneralSetting;
use App\Models\Loan;
use App\Models\LoanPlan;
use App\Models\PaidLog;
use App\Models\Savings;
use App\Models\SavingsPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{

    public function loanPlan()
    {
        $pageTitle      = 'Loan Plans';
        $emptyMessage   = 'No loan plan found';
        $loanPlans      = LoanPlan::where('status', 1)->latest()->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('staff.collection.loan_plan', compact('pageTitle', 'emptyMessage', 'loanPlans', 'days'));
    }

    public function loanApplyForm($planId)
    {
        $pageTitle  = 'Apply For Loan';
        $plan       = LoanPlan::where('status', 1)->findOrFail($planId);
        $days       = TimeInterval::select('name', 'day')->get()->toArray();

        return view('staff.collection.loan_apply', compact('pageTitle', 'plan', 'days'));
    }

    public function loanApply(Request $request, $planId)
    {
        $plan = LoanPlan::where('status',1)->findOrFail($planId);
        $user = User::where('username', $request->user)->orWhere('email', $request->user)->firstOrFail();
        $loanProcess = new LoanProcess(['plan'=>$plan,'user'=>$user, 'staff_id'=>auth('staff')->id()]);
        $request->validate($loanProcess->applyValidation());
        $apply = $loanProcess->apply();
        return back()->withNotify($apply);
    }


    public function loans()
    {
        $pageTitle = 'All loans';
        $emptyMessage = 'No loan found';
        $segments = request()->segments();
        $lastSegment = end($segments);
        $searchKey = request()->search;

        if($lastSegment == 'pending'){
            $pageTitle = 'Pending Loans';
            $loans = Loan::pending()->where('staff_id', auth('staff')->id());;
        }elseif($lastSegment == 'active'){
            $pageTitle = 'Active Loans';
            $loans = Loan::active();
        }elseif($lastSegment == 'paid'){
            $pageTitle = 'Paid Loans';
            $loans = Loan::paid();
        }elseif($lastSegment == 'closed'){
            $pageTitle = 'Closed Loans';
            $loans = Loan::closed();
        }else{
            $loans = Loan::query();
        }

        if($searchKey){
            $loans = $loans->where(function($query) use($searchKey){
                $query->whereHas('user', function($user) use($searchKey){
                    $user->where('username', 'LIKE', "%$searchKey%");
                })->orWhereHas('loanPlan', function($loanPlan) use($searchKey){
                    $loanPlan->where('name', 'LIKE', "%$searchKey%");
                });
            });
        }

        $loans = $loans->with(['user', 'loanPlan', 'paidLogs'=>function($paidLog){
            $paidLog->where('status', 1);
        }])->latest()->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('staff.collection.loan', compact('pageTitle', 'emptyMessage', 'loans', 'days'));
    }

    public function loanInstallment(Request $request)
    {
        $loan = Loan::with(['user', 'loanPlan', 'paidLogs'=>function($paidLog){
            $paidLog->where('status', 1);
        }])->findOrFail($request->loan_id);

        $user = $loan->user;
        $loanProcess = new LoanProcess([
            'user'=>$user,
        ]);

        $response = $loanProcess->installment($loan);
        if ($response['error'] == true) {
            return back()->withNotify($response['message']);
        }

        $trx               = getTrx();

        $paidLog                = new PaidLog();
        $paidLog->loan_id       = $request->loan_id;
        $paidLog->user_id       = $loan->user->id;
        $paidLog->staff_id      = auth('staff')->id();
        $paidLog->admin_receive = 0;
        $paidLog->amount        = $loan->installment;
        $paidLog->late_fee      = $loanProcess->lateFee;
        $paidLog->final_amount  = $loanProcess->amountWithLateFee;
        $paidLog->trx           = $trx;
        $paidLog->status        = 1;
        $paidLog->save();

        $loanProcess->updateLoan($loan);

        $general = GeneralSetting::first();

        notify($loan->user, 'LOAN_PAID', [
            'amount' => showAmount($loanProcess->amountWithLateFee),
            'currency' => $general->cur_text,
            'paid_by' => 'Staff',
            'loan_plan' => $loan->loanPlan->name
        ]);

        $notify[] = ['success', "Installment taken successfully"];
        return back()->withNotify($notify);
    }

    public function savingsPlan(){
        $pageTitle    = 'Savings Plans';
        $emptyMessage = 'No savings plan found';
        $savingsPlans = SavingsPlan::where('status', 1)->latest()->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('staff.collection.savings_plan', compact('pageTitle', 'emptyMessage', 'savingsPlans', 'days'));
    }

    public function savingsApplyForm($planId){
        $pageTitle = 'Apply For Savings';
        $plan = SavingsPlan::where('status', 1)->findOrFail($planId);
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('staff.collection.savings_apply', compact('pageTitle', 'plan', 'days'));
    }

    public function savingsApply(Request $request, $planId){
        $plan = SavingsPlan::where('status',1)->findOrFail($planId);
        $user = User::where('username', $request->user)->orWhere('email', $request->user)->firstOrFail();
        $savingsProcess = new SavingsProcess(['plan'=>$plan,'user'=>$user, 'staff_id'=>auth('staff')->id()]);
        $request->validate($savingsProcess->applyValidation());
        $apply = $savingsProcess->apply();
        return back()->withNotify($apply);
    }

    public function savings(){
        $pageTitle = 'All savings';
        $emptyMessage = 'No savings found';
        $segments = request()->segments();
        $lastSegment = end($segments);
        $searchKey = request()->search;

        if($lastSegment == 'pending'){
            $pageTitle = 'Pending Loans';
            $savingsList = Savings::pending()->where('staff_id', auth('staff')->id());
        }elseif($lastSegment == 'active'){
            $pageTitle = 'Active Savings';
            $savingsList = Savings::active();
        }elseif($lastSegment == 'paid'){
            $pageTitle = 'Paid Savings';
            $savingsList = Savings::paid();
        }elseif($lastSegment == 'closed'){
            $pageTitle = 'Closed Savings';
            $savingsList = Savings::closed();
        }else{
            $savingsList = Savings::query();
        }

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
        }])->latest()->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('staff.collection.savings', compact('pageTitle', 'emptyMessage', 'savingsList', 'days'));
    }

    public function savingsInstallment(Request $request){


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

        $trx               = getTrx();

        $paidLog                = new PaidLog();
        $paidLog->savings_id    = $request->savings_id;
        $paidLog->user_id       = $savings->user->id;
        $paidLog->staff_id      = auth()->guard('staff')->id();
        $paidLog->admin_receive = 0;
        $paidLog->amount        = $savings->installment;
        $paidLog->late_fee      = $savingsProcess->lateFee;
        $paidLog->final_amount  = $savingsProcess->amountWithLateFee;
        $paidLog->trx           = $trx;
        $paidLog->status        = 1;
        $paidLog->save();

        $savingsProcess->updateSavings($savings);

        $general = GeneralSetting::first();


        notify($savings->user, 'SAVINGS_PAID', [
            'amount' => showAmount($savingsProcess->amountWithLateFee),
            'currency' => $general->cur_text,
            'paid_by' => 'Staff',
            'savings_plan' => $savings->savingsPlan->name
        ]);

        $notify[] = ['success', "Installment taken successfully"];
        return back()->withNotify($notify);
    }

    public function checkUser(Request $request)
    {
        $user = User::where('username', $request->user)->orWhere('email', $request->user)->first();
        if(!$user){
            return false;
        }
        return $user;
    }

    public function dailyCollection(){
        $emptyMessage = 'No collection found';

        $segmentThree = request()->segment(2);
        $segmentFour = request()->segment(3);

        $collections = PaidLog::where('staff_id', auth('staff')->id());

        if($segmentThree == 'loan-collections'){
            $pageTitle = 'Loan Collections';
            $collections = $collections->where('loan_id', '!=', 0);
        }elseif($segmentThree == 'savings-collections'){
            $pageTitle = 'Savings Collections';
            $collections = $collections ->where('savings_id', '!=', 0);
        }
        $collections = $collections
        ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m-%d')")
        ->selectRaw('*, sum(final_amount) as total_amount, count(*) as count');
        if($segmentFour == 'pending'){
            $pageTitle = 'Pending '.$pageTitle;
            $collections = $collections->where('admin_receive', 0);
        }elseif($segmentFour == 'paid'){
            $pageTitle = 'Paid '.$pageTitle;
            $collections = $collections->where('admin_receive', 1);
        }

        $collections = $collections->with('staff')->latest()->paginate(getPaginate());

        return view('staff.collection.daily', compact('pageTitle', 'emptyMessage', 'collections'));
    }
    
    public function staffSavingCollection(){
        $pageTitle = 'Pending Savings Collections';
        $emptyMessage = 'No collection found';
        
        $pending = Savings::join('users', 'users.id', 'savings.user_id')
                                ->join('savings_plans', 'savings_plans.id', 'savings.savings_plan_id')
                                ->where('staff_id', Auth::guard('staff')->user()->id)
                                ->where('savings.status', 1)
                                ->select('users.*', 'savings.*', 'savings_plans.name', 'savings_plans.savings_type')
                                ->orderBy('next_payment', 'asc')
                                ->paginate(getPaginate());
        return view('staff.collection.savings.pending', compact('pageTitle', 'pending', 'emptyMessage'));
    }
    
    public function staffLoanCollection(){
        $pageTitle = 'Pending Loan Collections';
        $emptyMessage = 'No collection found';
        
        $pending = Loan::join('users', 'users.id', 'loans.user_id')
                                ->join('loan_plans', 'loan_plans.id', 'loans.loan_plan_id')
                                ->where('staff_id', Auth::guard('staff')->user()->id)
                                ->where('loans.status', 1)
                                ->select('users.*', 'loans.*', 'loan_plans.name', 'loan_plans.loan_type')
                                ->orderBy('next_pay', 'asc')
                                ->paginate(getPaginate());
        return view('staff.collection.loan.pending', compact('pageTitle', 'pending', 'emptyMessage'));
    }
    
    
    public function userSavings(Request $request){
        $savings = DB::table('savings')->where('id', $request->savings_id)->first();
        if(Auth::guard('staff')->user()->withdrawable_funds >= $savings->installment){
            
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
                
                // STAFF BALANCE UPPDATE
                DB::table('staffs')->where('id', Auth::guard('staff')->user()->id)->update([
                    'total_assets' => DB::raw('total_assets+'.$savings->savings_amount),
                    'active_savings_balance' => DB::raw('active_savings_balance-'.$savings->total_paid),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }else{
                DB::table('savings')->where('id', $request->savings_id)->update([
                   'last_payment' => date('Y-m-d'),
                   'next_payment' => date('Y-m-d', strtotime(' +'.$savings->installment_interval.' days')),
                ]);
                
                DB::table('staffs')->where('id', Auth::guard('staff')->user()->id)->update([
                    'active_savings_balance' => DB::raw('active_savings_balance+'.$savings->installment),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }
            // $savings_end = date('d-m-Y', strtotime(' +'.$duration.' days')); 
            
            PaidLog::create([
                'user_id' => $savings->user_id,
                'savings_id' => $savings->id,
                'savings_plan_id' => $savings->savings_plan_id,
                'staff_id' => Auth::guard('staff')->user()->id,
                'staff_pay' => Auth::guard('staff')->user()->id,
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
    
    public function userLoan(Request $request){
        $loan = DB::table('loans')->where('id', $request->loan_id)->first();
        if(Auth::guard('staff')->user()->withdrawable_funds >= $loan->installment){
            
            // USER loan UPDATE
           DB::table('loans')->where('id', $request->loan_id)->update([
               'total_paid' => $loan->total_paid + $loan->installment, 
               'installment_given' => $loan->installment_given + 1,
            ]);

            if($loan->total_installment == ($loan->installment_given + 1)){
                // loan STATUS UPDATE
                DB::table('loan')->where('id', $request->loan_id)->update([
                   'status' => 2,
                   'last_payment' => date('Y-m-d'),
                ]);
                
                // STAFF BALANCE UPPDATE
                DB::table('staffs')->where('id', Auth::guard('staff')->user()->id)->update([
                    'total_assets' => DB::raw('total_assets+'.$loan->loan_amount),
                    'active_savings_balance' => DB::raw('active_savings_balance-'.$loan->total_paid),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$loan->installment),
                ]);
            }else{
                DB::table('loans')->where('id', $request->loan_id)->update([
                   'last_pay' => date('Y-m-d'),
                   'next_pay' => date('Y-m-d', strtotime($loan->last_pay.' +'.$loan->installment_interval.' days')),
                ]);
                
                DB::table('staffs')->where('id',  Auth::guard('staff')->user()->id)->update([
                    'active_savings_balance' => DB::raw('active_savings_balance+'.$loan->installment),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$loan->installment),
                ]);
            }
            DB::table('fsps')->where('id', $loan->fsps_id)->update([
                    'withdrawable_funds' => DB::raw('withdrawable_funds+'.$loan->installment),
            ]);
            // $loan_end = date('d-m-Y', strtotime(' +'.$duration.' days')); 
            
            PaidLog::create([
                'user_id' => $loan->user_id,
                'loan_id' => $request->loan_id,
                'staff_id' =>  Auth::guard('staff')->user()->id,
                'fsp_id' => $loan->fsps_id,
                'loan_plan_id' => $loan->loan_plan_id,
                'staff_pay' =>  Auth::guard('staff')->user()->id,
                'amount' => $loan->installment,
                'final_amount' => $loan->installment,
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
