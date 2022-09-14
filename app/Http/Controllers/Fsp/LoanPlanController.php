<?php

namespace App\Http\Controllers\Fsp;

use App\Http\Controllers\Controller;
use App\Models\TimeInterval;
use App\Models\LoanPlan;
use App\Models\Fsp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoanPlanController extends Controller
{
    public function index()
    {
        $pageTitle = 'Loan Plans';
        $emptyMessage = 'No loan plan found';
        $loanPlans = LoanPlan::query();
        $searchKey = request()->search;

        if($searchKey){
            $loanPlans = $loanPlans->where('name', 'LIKE', "%$searchKey%");
        }
        $days = TimeInterval::select('name', 'day')->get()->toArray();

        $loanPlans = $loanPlans->where('fsp_id', Auth::guard('fsp')->user()->id)->latest()->paginate(getPaginate());
        $fsp = Fsp::where('id', Auth::guard('fsp')->user()->id)->first();

        return view('fsp.loans.loan', compact('pageTitle', 'emptyMessage', 'loanPlans', 'days', 'fsp'));
    }

    public function create()
    {
        $pageTitle = 'Add New Loan Plan';
        $days = TimeInterval::all();
        return view('fsp.loans.loan_form', compact('pageTitle', 'days'));
    }

    public function edit($id)
    {
        $plan = LoanPlan::findOrFail($id);
        $pageTitle = 'Edit Loan Plan';
        $days = TimeInterval::all();
        return view('admin.plan.loan_form', compact('pageTitle', 'plan', 'days'));
    }

    public function saveLoanPlan(Request $request, $id=0)
    {
        
        $request->validate([
            'name'                          => 'required',
            'installment_interval'          => 'required|integer|exists:time_intervals,day',
            'loan_amount'                   => 'required|numeric|gt:0',
            'payable_amount'                => 'required|numeric|gt:0',
            'total_installment'             => 'required|integer|min:1',
            'description'                   => 'required',
            'savings_history_period'        => 'required',
            'consistency_percentage'        => 'required',
            // 'consistency_percent_in_days'   => 'required',
            'min_savings_equity'            => 'required',
            'monthly_average_savings'       => 'required',
            'repayment_start'               => 'required',
            'no_of_benefactors'             => 'required',
            'fixed_late_fee'                => 'required|numeric|min:0',
            'percent_late_fee'              => 'required|numeric|min:0',
            'field_name.*'                  => 'sometimes|required'
        ],[
            'field_name.*.required'=>'All field is required'
        ]);
        
        $loanType = "";
        if($request->installment_interval == 7){
            $loanType = "weekly";
        }else if($request->installment_interval == 1){
            $loanType = "daily";
        }else if($request->installment_interval == 30){
            $loanType = "monthly";
        }
        
        $repayment_start = $request->repayment_start * 7;
        $repayment_start = date('Y-m-d', strtotime(' +'.$repayment_start.' days'));

        if($request->payable_amount <= $request->loan_amount){
            $notify[] = ['error', 'Payable amount must be greater than loan amount'];
            return back()->withNotify($notify);
        }

        $input_form = [];
        if ($request->has('field_name')) {
            for ($i = 0; $i < count($request->field_name); $i++) {

                $arr = [];
                $arr['field_name'] = titleToKey($request->field_name[$i]);
                $arr['field_level'] = $request->field_name[$i];
                $arr['type'] = $request->type[$i];
                $arr['validation'] = $request->validation[$i];
                $input_form[$arr['field_name']] = $arr;
            }
        }
        $loanPlan = new LoanPlan();
        $type = 'added';

        if($id){
            $loanPlan = LoanPlan::findOrFail($id);
            $type = 'updated';
        }

        $loanPlan->name                         = $request->name;
        $loanPlan->loan_amount                  = $request->loan_amount;
        $loanPlan->payable_amount               = $request->payable_amount;
        $loanPlan->installment                  = $request->payable_amount / $request->total_installment;
        $loanPlan->total_installment            = $request->total_installment;
        $loanPlan->installment_interval         = $request->installment_interval;
        $loanPlan->fixed_late_fee               = $request->fixed_late_fee;
        $loanPlan->percent_late_fee             = $request->percent_late_fee;
        $loanPlan->savings_history_period       = $request->savings_history_period;
        $loanPlan->consistency_percentage       = $request->consistency_percentage;
        $loanPlan->consistency_percent_in_days  = $request->consistency_percent_in_days;
        $loanPlan->min_savings_equity           = $request->min_savings_equity;
        $loanPlan->monthly_average_savings      = $request->monthly_average_savings;
        $loanPlan->repayment_start              = $repayment_start;
        $loanPlan->no_of_benefactors            = $request->no_of_benefactors;
        $loanPlan->user_data                    = $input_form;
        $loanPlan->description                  = $request->description;
        $loanPlan->loan_type                    = $loanType;
        $loanPlan->fsp_id                       = Auth::guard('fsp')->user()->id;
        $loanPlan->save();

        $notify[] = ['success', "Loan plan $type successfully"];
        return back()->withNotify($notify);
    }

    public function status(Request $request)
    {
        $plan = LoanPlan::findOrFail($request->plan_id);
        $plan->status = !$plan->status;
        $plan->save();

        $notify[] = ['success', $plan->name.($plan->status ? ' activated' : ' deactivated').' successfully'];
        return back()->withNotify($notify);
    }
}
