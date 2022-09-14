<?php

namespace App\Http\Controllers;

use App\Lib\LoanProcess;
use App\Models\GeneralSetting;
use App\Models\TimeInterval;
use App\Models\Loan;
use App\Models\Savings;
use App\Models\LoanPlan;
use App\Models\PaidLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SavingsCreditRating;

class LoanController extends Controller
{
    public function plan(){
        $pageTitle = 'Loan Plans';
        $emptyMessage = 'No loan plan found';
        $loanPlans = LoanPlan::join('fsps', 'loan_plans.fsp_id', 'fsps.id')->where('loan_plans.status', 1)->select('loan_plans.*', 'fsps.company_name', 'fsps.image')->latest()->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('user.loan.plan', compact('pageTitle', 'emptyMessage', 'loanPlans', 'days'));
    }

    public function applyForm($planId){
        // $plan = LoanPlan::where('status', 1)->findOrFail($planId);
        // $difference_days = "";
        // $saving_records = SavingsCreditRating::where('user_id', Auth::id());
        
        // if(count($saving_records->get()) > 0){
        //     $initial_savings_start = SavingsCreditRating::where('user_id', Auth::id())->orderBy('id', 'asc')->first();
        //     $initial_savings_end = SavingsCreditRating::where('user_id', Auth::id())->orderBy('id', 'desc')->first();
            
        //     $saving_records_end = $initial_savings_end->savings_date_ended ." 00:00:00";
        //     $saving_records_start = $initial_savings_start->savings_date_started ." 00:00:00";
            
        //     $start_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_end);
        //     $end_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_start);
            
        //     $difference_days = round($start_date->diffInDays($end_date)/30);
            
        //     // if(count($saving_records->get()) > 0){
        //     //     $difference_days = 0;
        //     // }else{
        //     //     $difference_days = round($start_date->diffInDays($end_date)/30);
        //     // }

            
        //     $savings_days_target_total = $saving_records->sum('savings_plan_duration') ;
        //     $savings_days_saved = $saving_records->sum('no_of_days_saved') ;
        //     $savings_days_percent = round(($savings_days_saved/$savings_days_target_total)*100, 6) ;
        //     $savings_amount_target_total = $saving_records->sum('savings_target_amount');
        //     $savings_amount_saved = $saving_records->sum('total_amount_saved');
        //     $savings_amount_percent = round(($savings_amount_saved/$savings_amount_target_total)*100, 6);
        //     $savings_average_amount_in_month = round(($savings_amount_saved/$savings_days_saved), 6) ;
        //     $savings_balance = round(Auth::user()->savings_balance, 2) ;
        //     $equity_percent = round(($savings_balance/$plan->loan_amount)*100, 2);
            
            
        //     $pageTitle = 'Apply For Loan';
        //     $plan = LoanPlan::where('status', 1)->findOrFail($planId);
        //     $days = TimeInterval::select('name', 'day')->get()->toArray();
    
        //     return view('user.loan.apply', compact('pageTitle', 'plan', 'days', 'savings_days_percent', 'equity_percent', 'savings_amount_percent', 'difference_days', 'savings_average_amount_in_month'));
        // }else{
        //     return redirect()->back()->with(session()->flash('error', "You must complete a savings cycle first before you can apply for any loan"));
        // }
        
        // OLD CODE END
        
        // NEW CODE STARTS
        
        $plan = LoanPlan::where('status', 1)->findOrFail($planId);
        $difference_days = "";
        $saving_records = Savings::where('user_id', Auth::id())->where('status', 2);
        
        if(count($saving_records->get()) > 0){        
            $initial_savings_start = Savings::where('user_id', Auth::id())->where('status', 2)->orderBy('savings_start', 'asc')->first();
            $initial_savings_end = Savings::where('user_id', Auth::id())->where('status', 2)->orderBy('savings_end', 'desc')->first();
            
            $saving_records_end = $initial_savings_end->savings_end ." 00:00:00";
            $saving_records_start = $initial_savings_start->savings_start ." 00:00:00";
            
            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d 00:00:00', $saving_records_end);
            $end_date = \Carbon\Carbon::createFromFormat('Y-m-d 00:00:00', $saving_records_start);
            
            $difference_days = round(($start_date->diffInDays($end_date)/30), 2);
            
            $savings_days_target_total = $saving_records->sum('total_installment') ;
            $savings_days_saved = $saving_records->sum('installment_given') ;
            $savings_days_percent = round(($savings_days_saved/$savings_days_target_total)*100, 2) ;
            $savings_amount_target_total = $saving_records->sum('savings_amount');
            $savings_amount_saved = $saving_records->sum('total_paid');
            $savings_amount_percent = round(($savings_amount_saved/$savings_amount_target_total)*100, 2);
            $savings_average_amount_in_month = round(($savings_amount_saved/$difference_days), 2) ;
            $savings_balance = round(Auth::user()->total_assets, 2) ;
            $equity_percent = round(($savings_balance/$plan->loan_amount)*100, 2);
            
            // return "$savings_average_amount_in_month";
            
            $pageTitle = 'Apply For Loan';
            $plan = LoanPlan::where('status', 1)->findOrFail($planId);
            $days = TimeInterval::select('name', 'day')->get()->toArray();
            
            $noBenefactors = count(DB::table('loans')->where('loan_plan_id', $planId)->where('status', 1)->get());
            $benefactorsLeft = $plan->no_of_benefactors - $noBenefactors;
    
            return view('user.loan.apply', compact('pageTitle', 'plan', 'days', 'benefactorsLeft', 'savings_balance', 'savings_average_amount_in_month', 'savings_amount_saved', 'savings_amount_target_total', 'initial_savings_start', 'initial_savings_end', 'savings_days_percent', 'equity_percent', 'savings_amount_percent', 'difference_days', 'savings_average_amount_in_month'));
        }else{
            return redirect()->back()->with(session()->flash('error', "You must complete a savings cycle first before you can apply for any loan"));
        }
        
        
    }

    public function apply(Request $request, $planId){
        $plan = LoanPlan::where('status', 1)->findOrFail($planId);
        // $saving_records_end = Savings::where('user_id', Auth::id())->orderBy('id', 'desc')->where('status', 3)->select('savings_end')->first();
        
        // if($saving_records_end = $saving_records_end->savings_end < date('d-m-Y')){
        //     return "yeah";
        // }else{
        //     return "No";
        // }
        
        // if($saving_records_end){
        //     $saving_records_end = $saving_records_end->savings_end ." 00:00:00";
        //     $saving_records_start = Savings::where('user_id', Auth::id())->orderBy('id', 'asc')->select('created_at')->first();
        //     $saving_records_start = date('d-m-Y', strtotime($saving_records_start->created_at)). " 00:00:00";
            
        //     $start_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_end);
        //     $end_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_start);
        //     $difference_days = round($start_date->diffInDays($end_date)/30);
            
        //     if($difference_days >= $plan->savings_history_period){
        //         return "yup";
        //     }else{
        //         return "You do not have upto $plan->savings_history_period months data";
        //     }
        // }else{
        //     return "You have not completed any savings cycle yet";
        // }
        
        
        

        
        // return $saving_records_start;
        // return $diff = $saving_records_end->diffInDays($saving_records_start);
        // $saving_records_history = date('d-m-Y', strtotime($saving_records_end->savings_end . ' - '.$plan->savings_history_period.' months'));
        // return $saving_records_history;
        
        // if(){
            
        // }else{
            
        // }
        
        // OLD USED START
        // $saving_records = SavingsCreditRating::where('user_id', Auth::id());
        // $initial_savings_start = SavingsCreditRating::where('user_id', Auth::id())->orderBy('id', 'asc')->first();
        // $initial_savings_end = SavingsCreditRating::where('user_id', Auth::id())->orderBy('id', 'desc')->first();
        
        // $saving_records_end = $initial_savings_end->savings_date_ended ." 00:00:00";
        // $saving_records_start = $initial_savings_start->savings_date_started ." 00:00:00";
        
        // $start_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_end);
        // $end_date = \Carbon\Carbon::createFromFormat('d-m-Y 00:00:00', $saving_records_start);
        // $difference_days = round($start_date->diffInDays($end_date)/30);
        
        // $savings_days_target_total = $saving_records->sum('savings_plan_duration');
        // $savings_days_saved = $saving_records->sum('no_of_days_saved');
        // $savings_days_percent = round(($savings_days_saved/$savings_days_target_total)*100, 6);
        // $savings_amount_target_total = $saving_records->sum('savings_target_amount');
        // $savings_amount_saved = $saving_records->sum('total_amount_saved');
        // $savings_amount_percent = round(($savings_amount_saved/$savings_amount_target_total)*100, 6);
        // $savings_average_amount_in_month = round(($savings_amount_saved/$savings_days_saved), 6);
        // $savings_balance = round(Auth::user()->savings_balance, 2);
        // $equity_percent = round(($savings_balance/$plan->loan_amount)*100, 2);
        
        // OLD USED END
        
        // NEW
        
        $saving_records = Savings::where('user_id', Auth::id())->where('status', 2);
        
            $initial_savings_start = Savings::where('user_id', Auth::id())->where('status', 2)->orderBy('savings_start', 'asc')->first();
            $initial_savings_end = Savings::where('user_id', Auth::id())->where('status', 2)->orderBy('savings_end', 'desc')->first();
            
            $saving_records_end = $initial_savings_end->savings_end ." 00:00:00";
            $saving_records_start = $initial_savings_start->savings_start ." 00:00:00";
            
            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d 00:00:00', $saving_records_end);
            $end_date = \Carbon\Carbon::createFromFormat('Y-m-d 00:00:00', $saving_records_start);
            
            $difference_days = round(($start_date->diffInDays($end_date)/30), 2);
            
            $savings_days_target_total = $saving_records->sum('total_installment') ;
            $savings_days_saved = $saving_records->sum('installment_given') ;
            $savings_days_percent = round(($savings_days_saved/$savings_days_target_total)*100, 2) ;
            $savings_amount_target_total = $saving_records->sum('savings_amount');
            $savings_amount_saved = $saving_records->sum('total_paid');
            $savings_amount_percent = round(($savings_amount_saved/$savings_amount_target_total)*100, 2);
            $savings_average_amount_in_month = round(($savings_amount_saved/$difference_days), 2) ;
            $savings_balance = round(Auth::user()->total_assets, 2) ;
            $equity_percent = round(($savings_balance/$plan->loan_amount)*100, 2);

        
        // END NEW
        
        
        
        
        // return $plan->min_savings_equity;
        
        // echo die;
        
        // return $difference_days;
        // echo die;
        // return "$initial_savings_start->savings_date_started - $initial_savings_end->savings_date_ended";
        
        // CONTINUE HERE TOMORROW
        if(count($saving_records->get()) > 0){
            if($difference_days >= $plan->savings_history_period){
                // if($savings_days_percent >= $plan->consistency_percent_in_days){
                    if($savings_amount_percent >= $plan->consistency_percentage){
                        if($equity_percent >= $plan->min_savings_equity){
                            if($savings_average_amount_in_month >= $plan->monthly_average_savings){
                                $loanProcess = new LoanProcess(['plan'=>$plan,'user'=>auth()->user(), 'fsps_id' => $plan->fsp_id]);
                                $request->validate($loanProcess->applyValidation());
                                $apply = $loanProcess->apply();
                                return back()->withNotify($apply);
                            }else{
                                return redirect()->back()->with(session()->flash('error', "Your Monthly average Savings of ($savings_average_amount_in_month) is less than the required minimum monthly savings of $plan->monthly_average_savings"));
                            }
                        }else{
                            return redirect()->back()->with(session()->flash('error', "Your savings Equity of ($equity_percent) % is less than the required Equity $plan->min_savings_equity %"));
                        }
                    }else{
                        return redirect()->back()->with(session()->flash('error', "Your savings amount consistency of ($savings_amount_percent) % is less than the required consistency of $plan->consistency_percentage %"));
                    }
                // }else{
                //     return redirect()->back()->with(session()->flash('error', "Your savings interval consistency of ($savings_days_percent) % is less than the required consistency of $plan->consistency_percent_in_days %"));
                // }
            }else{
                return redirect()->back()->with(session()->flash('error', "You do not have upto $plan->savings_history_period months data"));
            }
        }else{
            return redirect()->back()->with(session()->flash('error', "You have not completed any savings cycle yet"));
        }
        
        // echo die;
    }

    public function loans(){
        $emptyMessage = 'No loans history found';
        $segments = request()->segments();
        $lastSegment = end($segments);

        if($lastSegment == 'pending'){
            $pageTitle = 'Pending Loans';
            $loans = Loan::pending();
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
            $pageTitle = 'All Loans';
            $loans = Loan::query();
        }

        $loans = $loans->where('user_id', auth()->id())->with('loanPlan')->latest()->paginate(getPaginate());
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        return view('user.loan.index', compact('pageTitle', 'emptyMessage', 'loans', 'days'));
    }
    

    public function payment(Request $request){

        $request->validate([
            'loan_id' => 'required',
        ]);

        $loan = Loan::where('id', $request->loan_id)->where('user_id', auth()->id())->with('loanPlan')->firstOrFail();

        $trx = getTrx();
        $user =  auth()->user();
        $loanProcess = new LoanProcess(['user'=>$user]);
        
        $response = $loanProcess->installment($loan);
        if ($response['error'] == true) {
            return back()->withNotify($response['notify']);
        }

        $paidLog               = new PaidLog();
        $paidLog->loan_id      = $loan->id;
        $paidLog->user_id      = $user->id;
        $paidLog->amount       = $loan->installment;
        $paidLog->late_fee     = $loanProcess->lateFee;
        $paidLog->final_amount = $loanProcess->amountWithLateFee;
        $paidLog->trx          = $trx;
        $paidLog->save();

        session()->put('payment_data',[
            'paid_log_id' => $paidLog->id,
            'amount'      => $loanProcess->amountWithLateFee,
            'trx'         => $paidLog->trx
        ]);

        return redirect()->route('user.deposit');
    }
    
    public function walletPayment(request $request){
        
        $loan = DB::table('loans')->where('id', $request->loan_id)->first();
        if(Auth::user()->withdrawable_funds >= $loan->installment){
            
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
                
                // USER BALANCE UPPDATE
                DB::table('users')->where('id', Auth::id())->update([
                    'total_assets' => DB::raw('total_assets+'.$loan->loan_amount),
                    'active_savings_balance' => DB::raw('active_savings_balance-'.$loan->total_paid),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$loan->installment),
                    'loans_repaid' => DB::raw('loans_repaid+1'),
                ]);
            }else{
                DB::table('loans')->where('id', $request->loan_id)->update([
                   'last_pay' => date('Y-m-d'),
                   'next_pay' => date('Y-m-d', strtotime($loan->last_pay.' +'.$loan->installment_interval.' days')),
                ]);
                
                DB::table('users')->where('id', Auth::id())->update([
                    'active_savings_balance' => DB::raw('active_savings_balance+'.$loan->installment),
                    'withdrawable_funds' => DB::raw('withdrawable_funds-'.$loan->installment),
                ]);
            }
            DB::table('fsps')->where('id', $loan->fsps_id)->update([
                    'withdrawable_funds' => DB::raw('withdrawable_funds+'.$loan->installment),
            ]);
            // $loan_end = date('d-m-Y', strtotime(' +'.$duration.' days')); 
            
            PaidLog::create([
                'user_id' => Auth::id(),
                'loan_id' => $request->loan_id,
                'staff_id' => Auth::user()->agent_id,
                'fsp_id' => $loan->fsps_id,
                'loan_plan_id' => $loan->loan_plan_id,
                'user_pay' => Auth::id(),
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
