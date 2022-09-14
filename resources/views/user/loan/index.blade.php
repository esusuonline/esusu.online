@extends('user.layouts.app')
@section('panel')

@php 
    $today = date('Y-m-d');  
@endphp
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Loan Plan')</th>
                            <th>@lang('Total Payable')</th>
                            @if (request()->routeIs('user.loan.pending'))
                                <th>@lang('Applied Date')</th>
                            @else
                                <th>@lang('Paid')</th>
                            @endif
                            <th>@lang('Installment')</th>
                            <th>@lang('Installment Progress')</th>
                            <th>@lang('Repayment Type')</th>
                            <th>@lang('Loan Start')</th>
                            <th>@lang('Loan End')</th>
                            <th>@lang('Last / Next Repayment')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td data-label="@lang('S.N.')">{{ $loans->firstItem() + $loop->index }}</td>
                                <td data-label="@lang('Loan Plan')">{{ __($loan->loanPlan->name) }}</td>
                                <td data-label="@lang('Total Payable')">{{ $general->cur_sym }}{{ showAmount($loan->payable_amount) }}</td>
                                @if (request()->routeIs('user.loan.pending'))
                                    <td data-label="@lang('Applied Date')">{{ showDateTime($loan->created_at) }}</td>
                                @else
                                    <td data-label="@lang('Paid')">{{ $general->cur_sym }}{{ showAmount($loan->total_paid) }}</td>
                                @endif
                                <td data-label="@lang('Installment')">{{ $general->cur_sym }}{{ showAmount($loan->installment) }}</td>
                                @if($loan->status == 0)
                                    <td><span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span></td>
                                @else
                                    <td data-label="@lang('Installment Progress')">{{ $loan->installment_given }} / {{ $loan->total_installment }}</td>                              
                                @endif
                                <td data-label="@lang('Repayment_type')">
                                    @if($loan->installment_interval == 1)
                                        <span>Daily</span>
                                    @elseif($loan->installment_interval == 7)
                                        <span>Weekly</span>
                                    @elseif($loan->installment_interval == 30)
                                        <span>Monthly</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Loan Start')">{{ date('d-m-Y', strtotime($loan->loan_repay_start)) }}</td>
                                <td data-label="@lang('Loan End')">{{ date('d-m-Y', strtotime($loan->loan_repay_end)) }}</td>
                                <td data-label="@lang('Last/Next Repayment')">
                                    <span class="text-danger">Next Pay: {{ date('d-m-Y', strtotime($loan->next_pay)) }} 
                                        @if($loan->next_payment == $today)
                                        <i class="ml-1 fas fa-exclamation-triangle"></i>
                                        @endif
                                    </span><br>
                                    <span>Last Paid:<span>
                                        </span> 
                                            @if($loan->last_pay == NULL)
                                                N/A
                                            @else
                                                {{ date('d-m-Y', strtotime($loan->last_pay)) }}
                                            @endif
                                        </span>
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($loan->status == 0)
                                        <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                    @elseif($loan->status == 1)
                                        <span class="text--small badge font-weight-normal badge--primary">@lang('Active')</span>
                                    @elseif($loan->status == 2)
                                        <span class="text--small badge font-weight-normal badge--success">@lang('Paid')</span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--danger">@lang('Close')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <button class="icon-btn  ml-1 details-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')"
                                        data-plan_name = "{{ __($loan->loanPlan->name) }}"
                                        data-interval="{{ installmentInterval($loan->installment_interval, $days) }}@lang(' Payable')"
                                        data-loan_amount = "{{ getAmount($loan->loan_amount) }}"
                                        data-payable_amount = "{{ getAmount($loan->payable_amount) }}"
                                        data-total_paid="{{ getAmount($loan->total_paid) }}"
                                        data-installment = "{{ getAmount($loan->installment) }}"
                                        data-late_fee = "{{ $loan->next_installment < now()->format('Y-m-d') ? getAmount($loan->late_fee) : 0 }}"
                                        data-installment_remaining="{{ $loan->total_installment - $loan->installment_given }}"
                                        data-installment_given="{{ $loan->installment_given }}"
                                        data-last_installment="{{ $loan->last_installment ? showDateTime($loan->last_installment, 'M-d, y') : '-' }}"
                                        data-next_installment="{{ $loan->status == 1 ? showDateTime($loan->next_installment, 'M-d, y') : '-' }}"
                                        >
                                        <i class="las la-desktop"></i>
                                    </button>
                                    <button class="icon-btn btn--success installment-btn" data-toggle="tooltip" title="" data-original-title="@lang('Pay Now')"
                                        data-loan_id="{{ $loan->id }}"
                                        data-installment = "{{ getAmount($loan->installment) }}"
                                        data-late_fee = "{{ $loan->next_installment < now()->format('Y-m-d') ? getAmount($loan->late_fee) : 0 }}"
                                        {{ $loan->status != 1 ? 'disabled':'' }}>
                                          <i class="las la-comment-dollar"></i>
                                    </button>
                                </td>
                            </tr>
                            
                            <!--PAY FROM WALLET MODAL-->
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Payment Confirmation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form action="{{ route('user.loan.wallet.payment') }}" method="POST">
                                      @csrf
                                      <div class="modal-body text-center">
                                        <h5>&#8358; {{ number_format($loan->installment, 2) }} will be deducted from your wallet</h5>
                                        <br><br>
                                        <h5>Are you sure you want to continue ?</h5>
                                        <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel Payment</button>
                                        <button type="submit" class="btn btn-primary">Yes Make Payment</button>
                                      </div>
                                  </form>
                                  
                                </div>
                              </div>
                            </div>
                                
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($loans->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($loans) }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- LOAN DETAILS MODAL  --}}
<div id="loanModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Loan Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Loan Plan'):
                        <span class="loan-plan"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Loan Amount'):
                        <span class="loan-amount"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Payable'):
                        <span class="total-payable"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Paid')
                        <span class="total-paid"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Due')
                        <span class="total-due"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Instalment Interval'):
                        <span class="installment-interval"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Installment Given')
                        <span class="installment-given"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Installment Remaining')
                        <span class="installment-remaining"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Installment')
                        <span class="installment"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Late Fee')
                        <span class="late-fee"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Installment Payable')
                        <span class="installment-payable"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Last Installment')
                        <span class="last-installment"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Next Installment')
                        <span class="next-installment"></span>
                    </li>

                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

{{-- CONFIRM INSTALLMENT MODAL --}}
<div id="installmentModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Loan Installment Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.loan.payment')}}" method="POST">
                @csrf
                <input type="hidden" name="loan_id">
                <div class="modal-body">
                    <ul class="list-group mt-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Installment')
                            <span class="installment"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Late Fee')
                            <span class="late-fee"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Installment Payable')
                            <span class="installment-payable"></span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button id="payFromWallet" data-toggle="modal" data-target="#exampleModal" type="button" class="btn btn--primary btn-block">@lang('Pay From Wallet')</button>
                    <button type="button" class="btn btn--secondary btn-block">@lang('Pay With Card')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            var curSym = `{{ $general->cur_sym }}`;
            $('.details-btn').on('click', function(){
                var modal = $('#loanModal');
                var data = $(this).data();
                var installment = parseFloat(data.installment);
                var lateFee = parseFloat(data.late_fee);

                modal.find('.loan-plan').text(data.plan_name);
                modal.find('.loan-amount').text(curSym+data.loan_amount);
                modal.find('.total-payable').text(curSym+data.payable_amount);
                modal.find('.total-paid').text(curSym+data.total_paid);
                modal.find('.total-due').text(curSym+(data.payable_amount-data.total_paid).toFixed(2));
                modal.find('.installment-interval').text(data.interval);
                modal.find('.installment-remaining').text(data.installment_remaining);
                modal.find('.installment-given').text(data.installment_given);
                modal.find('.installment').text(curSym+installment);
                modal.find('.late-fee').text(curSym+lateFee.toFixed(2));
                modal.find('.installment-payable').text(curSym+(installment + lateFee).toFixed(2));
                modal.find('.last-installment').text(data.last_installment);
                modal.find('.next-installment').text(data.installment_remaining ? data.next_installment : '-');

                modal.modal('show');
            });
            
            // installmentModal
            $('#payFromWallet').on('click', function(){
                $("#installmentModal").modal('hide');
            })

            $('.installment-btn').on('click', function(){
                var modal = $('#installmentModal');
                var data = $(this).data();
                var installment = parseFloat(data.installment);
                var lateFee = parseFloat(data.late_fee);

                $('[name=loan_id]').val(data.loan_id);
                $('.installment').text(curSym+installment.toFixed(2));
                $('.late-fee').text(curSym+lateFee.toFixed(2));
                $('.installment-payable').text(curSym+(installment+lateFee).toFixed(2))
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
