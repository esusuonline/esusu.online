@extends('user.layouts.app')
@section('panel')

<style>
    /***********************************************/
/***************** Accordion ********************/
/***********************************************/
@import url('https://fonts.googleapis.com/css?family=Tajawal');
@import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');



#accordion-style-1 h1,
#accordion-style-1 a{
    color:#007b5e;
}
#accordion-style-1 .btn-link {
    font-weight: 400;
    color: #007b5e;
    background-color: transparent;
    text-decoration: none !important;
    font-size: 16px;
    font-weight: bold;
	padding-left: 25px;
}

#accordion-style-1 .card-body {
    border-top: 2px solid #007b5e;
}

#accordion-style-1 .card-header .btn.collapsed .fa.main{
	display:none;
}

#accordion-style-1 .card-header .btn .fa.main{
	background: #007b5e;
    padding: 13px 11px;
    color: #ffffff;
    width: 35px;
    height: 41px;
    position: absolute;
    left: -1px;
    top: 10px;
    border-top-right-radius: 7px;
    border-bottom-right-radius: 7px;
	display:block;
}
</style>

         @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center p-3" style="font-size:16px; background: #d9534f; color: white">
                  <strong>{{ session('error') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                      <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                  @endif

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>@lang('Loan Plan Information')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Plan Name')</label></strong> 
                            <span>{{ __($plan->name) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Loan Amount')</label></strong>
                            <span>{{ $general->cur_sym.showAmount($plan->loan_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Payable Amount')</label></strong>
                            <span>{{ $general->cur_sym.showAmount($plan->payable_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Total Installment')</label></strong>
                            <span>{{ $plan->total_installment }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Installment Amount')</label></strong>
                            <span>{{ $general->cur_sym.showAmount($plan->installment) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Installment Interval')</label></strong>
                            <span>{{ installmentInterval($plan->installment_interval, $days) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Late Fee')</label></strong>
                            <span> {{ $general->cur_sym.showAmount($plan->fixed_late_fee + ($plan->installment * $plan->percent_late_fee / 100)) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('No Of Benefactors')</label></strong>
                            <span> {{ $plan->no_of_benefactors }} <span class="badge badge-warning text-dark font-weight-bold">{{ $benefactorsLeft }} slots Left</span></span>
                        </li>
                        <li class="list-group-item">
                           <strong><label>@lang('Description')</label></strong> <hr class="mt-1 mb-2">
                           @php
                               echo $plan->description;
                           @endphp
                        </li>
                      </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-6 p-0">
            
             <div class="container-fluid bg-gray px-0" id="accordion-style-1">
                    	<div class="container px-0">
                    		<section>
                    			<div class="row">
                    				<div class="col-12 mx-auto">

                                        <h5 class="text-center mb-3 mt-n4">@lang('Loan Credit Rating Appraisal')</h5>

                    					<div class="accordion" id="accordionExample">
                    						<div style="border-bottom: 2px solid blue" class="card text-dark">
                    							<div class="card-header" id="headingOne">
                    								<h5 class="mb-0">
                    							<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    							  <i class="fa fa-minus main"></i><i class="fa fa-angle-double-right mr-3"></i>
                    							  <strong><label>@lang('Minimum Savings Record')</label></strong>
                                                    <span>({{ __($plan->savings_history_period) }} Months Records)</span>
                                                    &nbsp; &nbsp; &nbsp; <span class="float-right">[You: {{ $difference_days }} month(s)] @if ($difference_days >= $plan->savings_history_period) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>

                    							</button>
                    						  </h5>
                    							</div>
                    
                    							<div style="border-bottom: 2px solid blue" id="collapseOne" class="collapse show fade" aria-labelledby="headingOne" data-parent="#accordionExample">
                    								<div class="card-body">
                    									 <div class="font-weight-bold mb-1">ANALYSIS:</div>
                                                        <div class="mb-1">The <strong>FSP</strong> needs a minimum {{ __($plan->savings_history_period) }} month(s) 
                                                        records of all the saving cycles you have completed. This is to validate your time credibilty on the platform</div>
                                                        You have completed savings for {{ $difference_days }} month(s). <br>
                                                        Your first <span class="text-secondary">completed</span> saving cycle started on 
                                                        <span class="font-weight-bold text-primary">{{ date('d - F - Y', strtotime($initial_savings_start->savings_start)) }}</span>
                                                        and your last <span class="text-secondary">completed</span> saving cycle was completed on 
                                                        <span class="font-weight-bold text-primary">{{ date('d - F - Y', strtotime($initial_savings_start->savings_end)) }}</span>
                                                        <p>Appraisal Status: 
                                                            @if ($difference_days >= $plan->savings_history_period) &nbsp;<span><i class="fas fa-check text-success"></i> <span class="text-success">PASS</span></span> 
                                                            @else &nbsp;<span><i class="fas fa-times text-danger"></i> <span class="text-danger">FAIL</span> </span> 
                                                            @endif
                                                        </p>
                    								</div>
                    							</div>
                    						</div>
                    						<div style="border-bottom: 2px solid blue" class="card text-dark">
                    							<div class="card-header" id="headingTwo">
                    								<h5 class="mb-0">
                    							<button class="btn btn-link collapsed btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    							 <i class="fa fa-plus main"></i><i class="fa fa-angle-double-right mr-3"></i>
                    							    <strong><label>@lang('Savings Amount Consistency')</label></strong>
                                                    <span>({{ __($plan->consistency_percentage) }} % )</span>
                                                    &nbsp; &nbsp; &nbsp; <span class="float-right">[You: {{ $savings_amount_percent }} %] @if ($savings_amount_percent >= $plan->consistency_percentage) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>
                                                
                    							</button>
                    						  </h5>
                    							</div>
                    							<div style="border-bottom: 2px solid blue" id="collapseTwo" class="collapse fade" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    								<div class="card-body">
                    									 <div class="font-weight-bold mb-1">ANALYSIS:</div>
                                                        <div class="mb-1">The <strong>FSP</strong> requires that you meet up to {{ __($plan->savings_history_period) }} % of your accumulated savings total
                                                        from {{ date('d - m - Y', strtotime($initial_savings_start->savings_start)) }} to {{ date('d - m - Y', strtotime($initial_savings_start->savings_end)) }}. 
                                                        This is to validate your savings amount and interval consistency.</div>
                                                        
                                                        The accummulated target for all your saving plans from {{ date('d - F - Y', strtotime($initial_savings_start->savings_start)) }} - 
                                                        {{ date('d - F - Y', strtotime($initial_savings_start->savings_end)) }} is:  &#8358; {{ number_format($savings_amount_target_total, 2) }}. 
                                                        You were able to save &#8358; {{ number_format($savings_amount_saved, 2) }} within this period. This is {{ $savings_amount_percent }} % consistency ratio.
                                                        <br>
                                                        
                                                        <p>Appraisal Status: 
                                                            @if ($savings_amount_percent >= $plan->consistency_percentage) &nbsp;<span><i class="fas fa-check text-success"></i> <span class="text-success">PASS</span></span> 
                                                            @else &nbsp;<span><i class="fas fa-times text-danger"></i> <span class="text-danger">FAIL</span></span> 
                                                            @endif
                                                        </p>
                                                    </div>
                    								</div>
                    							</div>
                    						<div style="border-bottom: 2px solid blue" class="card text-dark">
                    							<div class="card-header" id="headingThree">
                    								<h5 class="mb-0">
                    							<button class="btn btn-link collapsed btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    							  <i class="fa fa-expeditedssl main"></i><i class="fa fa-angle-double-right mr-3"></i>
                    							  <strong><label>@lang('Minimum Savings Equity')</label></strong>
                                                    <span>({{ __($plan->min_savings_equity) }} % [{{ $general->cur_sym.showAmount(($plan->min_savings_equity/100) * $plan->loan_amount) }}])</span>
                                                    &nbsp; &nbsp; &nbsp; <span class="float-right">[You: {{ $equity_percent }} %] @if ($equity_percent >= $plan->min_savings_equity) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>
                                                
                    							</button>
                    						  </h5>
                    							</div>
                    							<div style="border-bottom: 2px solid blue" id="collapseThree" class="collapse fade" aria-labelledby="headingThree" data-parent="#accordionExample">
                    								<div class="card-body">
                                                        <div class="font-weight-bold mb-1">ANALYSIS:</div>
                                                            <div class="mb-1">The <strong>FSP</strong> requires that you should have at least {{ __($plan->min_savings_equity) }} % of the loan available in your assets.
                                                            This is to hold as a commitment.</div>
                                                            
                                                            You currently have &#8358; {{ number_format($savings_balance, 2) }} in your assets. This is {{ $equity_percent }} % of the loan plan amount (&#8358; {{ number_format($plan->loan_amount, 2) }})
                                                            <br>
                                                            
                                                            <p>Appraisal Status: 
                                                                
                                                                @if ($equity_percent >= $plan->min_savings_equity) &nbsp;<span><i class="fas fa-check text-success"></i> <span class="text-success">PASS</span></span> 
                                                                @else &nbsp;<span><i class="fas fa-times text-danger"></i> <span class="text-danger">FAIL</span></span> 
                                                                @endif
                            
                                                            </p>
                                                        </div>
                    								</div>
                    							</div>
                    						<div style="border-bottom: 2px solid blue" class="card text-dark">
                    							<div class="card-header" id="headingFour">
                    								<h5 class="mb-0">
                    							<button class="btn btn-link collapsed btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    							  <i class="fa fa-envelope main"></i><i class="fa fa-angle-double-right mr-3"></i>
                    							  <strong><label>@lang('Monthly Average Savings')</label></strong>
                                                    <span>({{ $general->cur_sym.showAmount($plan->monthly_average_savings) }})</span>
                                                    &nbsp; &nbsp; &nbsp; <span class="float-right">[You: &#8358; {{ $savings_average_amount_in_month }}] @if ($savings_average_amount_in_month >= $plan->monthly_average_savings) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>

                    							</button>
                    						  </h5>
                    							</div>
                    							<div style="border-bottom: 2px solid blue" id="collapseFour" class="collapse fade" aria-labelledby="headingFour" data-parent="#accordionExample">
                    								<div class="card-body">
                    								    <div class="font-weight-bold mb-1">ANALYSIS:</div>
                                                            <div class="mb-1">The <strong>FSP</strong> requires that your monthly average savings over the last {{ $difference_days }} month(s) month(s) is at least &#8358; {{ number_format($plan->monthly_average_savings, 2) }}.
                                                            This is to hold as a commitment.</div>
                                                            
                                                            Your current monthly average savings is: {{ number_format($savings_average_amount_in_month, 2) }}
                                                            <br>
                                                            
                                                            <p>Appraisal Status: 
                                                                
                                                                @if ($savings_average_amount_in_month >= $plan->monthly_average_savings) &nbsp;<span><i class="fas fa-check text-success"></i> <span class="text-success">PASS</span></span> 
                                                                @else &nbsp;<span><i class="fas fa-times text-danger"></i> <span class="text-danger">FAIL</span></span> 
                                                                @endif
                            
                                                            </p>
                    								</div>
                    							</div>
                    						</div>
                    					</div>
                    				</div>	
                    			</div>
                    		</section>
                    	</div>
                    </div>
                    
            <?php
            /*
            <div class="card">
                <div class="card-header">
                    <h5>@lang('Credit Rating Credentials')</h5>
                </div>
                <div class="card-body">
                    
                   


                    <ul class="list-group">
                        
                        
                        <div class="mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong><label>@lang('Minimum Savings Record')</label></strong>
                                <span>({{ __($plan->savings_history_period) }} Months Records)</span>
                                &nbsp; &nbsp; &nbsp; <span>[You: {{ $difference_days }}] @if ($difference_days >= $plan->savings_history_period) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>
                                <br>
                            </li>
                            <div class="card-text">
                                
                            </div>
                        </div>
                        <hr>
                        

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong><label>@lang('Savings Interval Consistency')</label></strong>
                            <span>({{ __($plan->consistency_percent_in_days) }} % )</span> 
                            &nbsp; &nbsp; &nbsp; <span>[You: {{ $savings_days_percent }}] @if ($savings_days_percent >= $plan->consistency_percent_in_days) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>
                        </li>

                        
                        <div class="mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong><label>@lang('Savings Amount Consistency')</label></strong>
                                <span>({{ __($plan->consistency_percentage) }} % )</span>
                                &nbsp; &nbsp; &nbsp; <span>[You: {{ $savings_amount_percent }}] @if ($savings_amount_percent >= $plan->consistency_percentage) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>
                            </li>
                            <div class="card-text">
                                <div class="font-weight-bold mb-1">ANALYSIS:</div>
                                <div class="mb-1">The <strong>FSP</strong> requires that you meet up to {{ __($plan->savings_history_period) }} % of your accumulated savings total
                                from {{ date('d - m - Y', strtotime($initial_savings_start->savings_start)) }} to {{ date('d - m - Y', strtotime($initial_savings_start->savings_end)) }}. 
                                This is to validate your savings amount and interval consistency.</div>
                                
                                The accummulated target for all your saving plans from {{ date('d - F - Y', strtotime($initial_savings_start->savings_start)) }} - 
                                {{ date('d - F - Y', strtotime($initial_savings_start->savings_end)) }} is:  &#8358; {{ number_format($savings_amount_target_total, 2) }}. 
                                You were able to save &#8358; {{ number_format($savings_amount_saved, 2) }} within this period. This is {{ $savings_amount_percent }} % consistency ratio.
                                <br>
                                
                                <p>Appraisal Status: 
                                    @if ($savings_amount_percent >= $plan->consistency_percentage) &nbsp;<span><i class="fas fa-check text-success"></i> <span class="text-success">PASS</span></span> 
                                    @else &nbsp;<span><i class="fas fa-times text-danger"></i> <span class="text-danger">FAIL</span></span> 
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong><label>@lang('Minimum Savings Equity')</label></strong>
                                <span>({{ __($plan->min_savings_equity) }} % ({{ $general->cur_sym.showAmount(($plan->min_savings_equity/100) * $plan->loan_amount) }}))</span>
                                &nbsp; &nbsp; &nbsp; <span>[You: {{ $equity_percent }}] @if ($equity_percent >= $plan->min_savings_equity) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>
                            </li>
                            
                            <div class="card-text">
                                <div class="font-weight-bold mb-1">ANALYSIS:</div>
                                <div class="mb-1">The <strong>FSP</strong> requires that you should have at least {{ __($plan->min_savings_equity) }} % of the loan available in your assets.
                                This is to hold as a commitment.</div>
                                
                                You currently have &#8358; {{ number_format($savings_balance, 2) }} in your assets. This is {{ $equity_percent }} % of the loan plan amount (&#8358; {{ number_format($plan->loan_amount, 2) }})
                                <br>
                                
                                <p>Appraisal Status: 
                                    
                                    @if ($equity_percent >= $plan->min_savings_equity) &nbsp;<span><i class="fas fa-check text-success"></i> <span class="text-success">PASS</span></span> 
                                    @else &nbsp;<span><i class="fas fa-times text-danger"></i> <span class="text-danger">FAIL</span></span> 
                                    @endif

                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong><label>@lang('Monthly Average Savings')</label></strong>
                                <span>({{ $general->cur_sym.showAmount($plan->monthly_average_savings) }})</span>
                                &nbsp; &nbsp; &nbsp; <span>[You: {{ $savings_average_amount_in_month }}] @if ($savings_average_amount_in_month >= $plan->monthly_average_savings) &nbsp;<span><i class="fas fa-check text-success"></i></span> @else &nbsp;<span><i class="fas fa-times text-danger"></i></span> @endif</span>
                                
                            </li>
                            
                            <div class="card-text">
                                <div class="font-weight-bold mb-1">ANALYSIS:</div>
                                <div class="mb-1">The <strong>FSP</strong> requires that your monthly average savings over the last {{ $difference_days }} month(s) month(s) is at least &#8358; {{ number_format($plan->monthly_average_savings, 2) }}.
                                This is to hold as a commitment.</div>
                                
                                Your current monthly average savings is: {{ number_format($savings_average_amount_in_month, 2) }}
                                <br>
                                
                                <p>Appraisal Status: 
                                    
                                    @if ($savings_average_amount_in_month >= $plan->monthly_average_savings) &nbsp;<span><i class="fas fa-check text-success"></i> <span class="text-success">PASS</span></span> 
                                    @else &nbsp;<span><i class="fas fa-times text-danger"></i> <span class="text-danger">FAIL</span></span> 
                                    @endif

                                </p>
                            </div>
                        </div>
                        
                        
                      </ul>
                     

                </div>
            </div>
             */
            ?>
            
            <div class="card my-5">
                <div class="card-header">
                    <h5>@lang('Additional User Information')</h5>
                </div>
                <form action="{{ route('user.loan.apply', $plan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if($plan->user_data)
                            @foreach($plan->user_data as $k => $v)
                                @if($v->type == "text")
                                    <div class="form-group">
                                        <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                        <input type="text" name="{{$k}}" class="form-control" value="{{old($k)}}" placeholder="{{__($v->field_level)}}" @if($v->validation == "required") required @endif>
                                        @if ($errors->has($k))
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "textarea")
                                    <div class="form-group">
                                        <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                        <textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3" @if($v->validation == "required") required @endif>{{old($k)}}</textarea>
                                        @if ($errors->has($k))
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "file")
                                    <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                    <div class="form-group">
                                        <input type="file" class="form-control file-input-custom" name="{{$k}}" accept="image/*" @if($v->validation == "required") required @endif>

                                        @if ($errors->has($k))
                                            <br>
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    @php
                        $check = DB::table('loans')->where('user_id', Auth::id())->where('loan_plan_id', $plan->id)->first();
                    @endphp
                    <div class="card-footer">
                        <div class="form-group">
                            @if(!$check)
                                <button type="submit" class="btn btn--primary btn-block">@lang('Submit Application')</button>
                            @elseif($check->status == 0)
                                <button type="button" disabled class="btn btn--warning btn-block">@lang('Applied : (Pending)')</button>
                            @elseif($check->status == 1)
                                <button type="button" disabled class="btn btn--secondary btn-block">@lang('Activated')</button>
                             @elseif($check->status == 2)
                                <button type="submit" class="btn btn--primary btn-block">@lang('Submit Application')</button>
                            @elseif($check->status == 3)
                                <button type="submit" class="btn btn--primary btn-block">@lang('Submit Application')</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            
            
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('user.loan.plan') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush
