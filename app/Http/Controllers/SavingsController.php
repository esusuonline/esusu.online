<?php

namespace App\Http\Controllers;

use App\Lib\SavingsProcess;
use App\Models\GeneralSetting;
use App\Models\TimeInterval;
use App\Models\PaidLog;
use App\Models\Savings;
use App\Models\SavingsPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SavingsController extends Controller
{
    public function plan(){
        $pageTitle = 'Savings Plans';
        $emptyMessage = 'No savings plan found';
        $savingsPlans = SavingsPlan::where('status', 1)->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('user.savings.plan', compact('pageTitle', 'emptyMessage', 'savingsPlans', 'days'));
    }

    public function applyForm($planId){
        $pageTitle = 'Apply For Savings';
        $plan = SavingsPlan::where('status', 1)->findOrFail($planId);
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('user.savings.apply', compact('pageTitle', 'plan', 'days'));
    }

    public function apply(Request $request, $planId){
        $plan = SavingsPlan::where('status',1)->findOrFail($planId);
        $duration = $plan->total_installment * $plan->installment_interval;
        // return date('d-m-Y', strtotime($request->savings_duration . ' + 3 months + 1 day'));
        // $savings_end = date('Y-m-d', strtotime(' +'.$duration.' days')); 
        // $savings_start = date('Y-m-d'); 
        // $duration = $plan->total_installment; 
        
        $savingsProcess = new SavingsProcess(['plan'=>$plan,'user'=>auth()->user(), 'duration' => $duration, 'agent_id' => Auth::user()->agent_id]);
        $request->validate($savingsProcess->applyValidation());
        $apply = $savingsProcess->apply();
        return back()->withNotify($apply);
    }

    public function savings(){
        $emptyMessage = 'No savings history found';
        $segments = request()->segments();
        $lastSegment = end($segments);

        if($lastSegment == 'pending'){
            $pageTitle = 'Pending savings';
            $savingsList = Savings::pending();
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
            $pageTitle = 'All Savings';
            $savingsList = Savings::query();
        }

        $savingsList = $savingsList->where('user_id', auth()->id())->with('savingsPlan')->latest()->paginate(getPaginate());
        // $savingsList = $savingsList->join('savings_plan', 'savings_plan.id', 'savings.savings_plan_id')->where('user_id', Auth::id())->get();

        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('user.savings.index', compact('pageTitle', 'emptyMessage', 'savingsList', 'days'));
    }

    public function payment(Request $request){
        $request->validate([
            'savings_id' => 'required',
        ]);

        $savings = Savings::where('id', $request->savings_id)->where('user_id', auth()->id())->with('savingsPlan')->firstOrFail();

        $trx = getTrx();
        $user =  auth()->user();
        $savingsProcess = new SavingsProcess([
            'user'=>$user,
        ]);
    
        $response = $savingsProcess->installment($savings);
        if ($response['error'] == true) {
            return back()->withNotify($response['notify']);
        }

        $paidLog               = new PaidLog();
        $paidLog->savings_id   = $savings->id;
        $paidLog->user_id      = auth()->id();
        $paidLog->amount       = $savings->installment;
        $paidLog->late_fee     = $savingsProcess->lateFee;
        $paidLog->final_amount = $savingsProcess->amountWithLateFee;
        $paidLog->trx          = $trx;

        $paidLog->save();
        session()->put('payment_data',[
            'paid_log_id' => $paidLog->id,
            'amount'  => $savingsProcess->amountWithLateFee,
            'trx'         => $paidLog->trx
        ]);
        return redirect()->route('user.deposit');
    }
    
    public function walletPayment(request $request){
        
        $savings = DB::table('savings')->where('id', $request->savings_id)->first();
        
        if(Auth::user()->withdrawable_funds >= $savings->installment){
            
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
                
                // USER BALANCE UPPDATE
                DB::table('users')->where('id', Auth::id())->update([
                    'total_assets' => DB::raw('total_assets+'.$savings->savings_amount),
                    'active_savings_balance' => DB::raw('active_savings_balance-'.$savings->total_paid),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }else{
                DB::table('savings')->where('id', $request->savings_id)->update([
                   'last_payment' => date('Y-m-d'),
                   'next_payment' => date('Y-m-d', strtotime(' +'.$savings->installment_interval.' days')),
                ]);
                
                DB::table('users')->where('id', Auth::id())->update([
                    'active_savings_balance' => DB::raw('active_savings_balance+'.$savings->installment),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }
            // $savings_end = date('d-m-Y', strtotime(' +'.$duration.' days')); 
            
            PaidLog::create([
                'user_id' => Auth::id(),
                'savings_id' => $request->savings_id,
                'staff_id' => Auth::user()->agent_id,
                'savings_plan_id' => $savings->savings_plan_id,
                'user_pay' => Auth::id(),
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
    
    public function cardPayment(Request $request){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$request->transaction_id}/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer FLWSECK_TEST-78eee241c513312a25fe3513ef684008-X"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // dd($response->status);
        $res = json_decode($response);

        // date_default_timezone_set('Africa/Lagos');
        
        if ($res->data->status === "successful") {  // DO FOR AMOUNT AND CURRENCY CHECK TOO USIN AND COMPARISION (AMOUNT DIDNT MATCH COS 100.00 is DIFF FROM 100... BUT ALL REQUESTS CAME)
            // return "Yeah - $request->savings_id - $request->amount - $request->currency";
            
             $savings = DB::table('savings')->where('id', $request->savings_id)->first();
            
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
                
                // USER BALANCE UPPDATE
                DB::table('users')->where('id', Auth::id())->update([
                    'total_assets' => DB::raw('total_assets+'.$savings->savings_amount),
                    'active_savings_balance' => DB::raw('active_savings_balance-'.$savings->total_paid),
                    // 'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }else{
                DB::table('savings')->where('id', $request->savings_id)->update([
                   'last_payment' => date('Y-m-d'),
                   'next_payment' => date('Y-m-d', strtotime(' +'.$savings->installment_interval.' days')),
                ]);
                
                DB::table('users')->where('id', Auth::id())->update([
                    'active_savings_balance' => DB::raw('active_savings_balance+'.$savings->installment),
                    // 'withdrawable_funds' => DB::raw('withdrawable_funds-'.$savings->installment),
                ]);
            }
            // $savings_end = date('d-m-Y', strtotime(' +'.$duration.' days')); 
            
            PaidLog::create([
                'user_id' => Auth::id(),
                'savings_id' => $request->savings_id,
                'staff_id' => Auth::user()->agent_id,
                'savings_plan_id' => $savings->savings_plan_id,
                'user_pay' => Auth::id(),
                'amount' => $savings->installment,
                'final_amount' => $savings->installment,
                'trx' => $res->data->tx_ref,
                'status' => 1,
            ]);
            $notify[] = ['success', 'Installment taken successfully'];
            
            return response()->json([
                'status' => 200,
                // 'errors' => $validator->messages()
            ]);
            return redirect()->back()->withNotify($notify);
            
        } else {
            return response()->json([
                'status' => 400,
                // 'errors' => $validator->messages()
            ]);
           $notify[] = ['error', 'Sorry, your payment cannot be processed now! Please, try again later'];
            return redirect()->back()->withNotify($notify);
        }
        
        
    }
    
}



