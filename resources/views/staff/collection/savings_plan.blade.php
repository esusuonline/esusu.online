@extends('staff.layouts.app')
@section('panel')
<div class="row mt-50 mb-none-30">
    @foreach ($savingsPlans as $plan)     
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card">
                <div class="card-body p-4 p-xxl-5">
                    <div class="pricing-table text-left">
                    <h4 class="package-name mb-20">{{ ucwords($plan->name) }}</h4>
                    <h5><span class="text--dark font-weight-bold">{{ $general->cur_sym }}{{ showAmount($plan->installment) }}</span></h5>
                    <p>{{ ucwords($plan->savings_type) }} Savings</p>
                    <ul class="list-group list-group-flush my-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            @lang('Savings amount')
                            <span>{{ $general->cur_sym }}{{ showAmount($plan->savings_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            @lang('Receivable amount')
                          <span>{{ $general->cur_sym }}{{ showAmount($plan->giveable_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                          @lang('Total Installment')
                          <span>
                              {{ $plan->total_installment }}
                              
                              @if($plan->savings_type == "daily")
                                <span>(Days)</span>
                              @elseif($plan->savings_type == "weekly")
                                <span>(Weeks)</span>
                              @elseif($plan->savings_type == "monthly")
                                <span>(Months)</span>
                              @endif
                          </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                          @lang('Late Fee')
                          <span>{{ $general->cur_sym }}{{ showAmount($plan->fixed_late_fee + ($plan->installment * $plan->percent_late_fee / 100)) }}</span>
                        </li>
                      </ul>
                      <?php
                      /*
                         @php
                            $check = DB::table('savings')->where('savings_plan_id', $plan->id)->where('staff_id', Auth::guard('staff')->user()->id)->orderBy('id', 'desc')->first();
                        @endphp
                        
                        @if(!$check)
                            <a href="{{ route('staff.savings.apply', $plan->id) }}" class="btn w-100 btn--primary py-2 box--shadow1">@lang('Apply Now')</a>
                        @elseif($check->status == 0)
                            <button type="button" disabled class="btn w-100 btn--warning py-2 box--shadow1">@lang('Applied : (Pending)')</button>
                        @elseif($check->status == 1)
                            <button type="button" disabled class="btn w-100 btn--secondary py-2 box--shadow1">@lang('Activated')</button>
                         @elseif($check->status == 2)
                            <a href="{{ route('staff.savings.apply', $plan->id) }}" class="btn w-100 btn--primary py-2 box--shadow1">@lang('Apply Now')</a>
                        @elseif($check->status == 3)
                            <a href="{{ route('staff.savings.apply', $plan->id) }}" class="btn w-100 btn--primary py-2 box--shadow1">@lang('Apply Now')</a>
                        @endif
                        
                        */
                        ?>
                        
                        <a href="{{ route('staff.savings.apply', $plan->id) }}" class="btn w-100 btn--primary py-2 box--shadow1">@lang('Apply Now')</a>
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    @endforeach
</div>
@endsection

@push('style')
    <style>
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center
        }
        .header-search-wrapper {
            gap: 15px
        }
        @media (max-width:400px) {
            .header-search-form {
                width: 100%
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.statusBtn').on('click', function () {
                var modal = $('#statusModal');
                var status = $(this).data('status');
                var name = $(this).data('name');
                modal.find('input[name=plan_id]').val($(this).data('id'));

                if(status == 1){
                    $('.modal-title').text("@lang('Plan Inactivate Confirmation')");
                    $('.message').html(`@lang('Are you sure to inactivate') <span class="font-weight-bold">${name}</span> @lang('plan')?`);
                }else{
                    $('.modal-title').text("@lang('Plan Activate Confirmation')");
                    $('.message').html(`@lang('Are you sure to activate') <span class="font-weight-bold">${name}</span> @lang('plan')?`);
                }
            });

        })(jQuery);
    </script>
@endpush
