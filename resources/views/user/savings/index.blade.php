@extends('user.layouts.app')
@section('panel')

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Savings Plan')</th>
                            <th>@lang('Savings Amount')</th>
                            @if (request()->routeIs('user.savings.pending'))
                                <th>@lang('Applied Date')</th>
                            @else
                                <th>@lang('Paid')</th>
                            @endif
                            <th>@lang('Installment')</th>
                            <th>@lang('Installment Progress')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($savingsList as $savings)
                            <tr>
                                <td data-label="@lang('S.N.')">{{ $savingsList->firstItem() + $loop->index }}</td>
                                <td data-label="@lang('Savings Plan')">{{ __($savings->savingsPlan->name) }}</td>
                                <td data-label="@lang('Savings Amount')">{{ $general->cur_sym }}{{ showAmount($savings->savings_amount) }}</td>
                                @if (request()->routeIs('user.savings.pending'))
                                    <td data-label="@lang('Applied Date')">{{ showDateTime($savings->created_at) }}</td>
                                @else
                                    <td data-label="@lang('Paid')">{{ $general->cur_sym }}{{ showAmount($savings->total_paid) }}</td>
                                @endif
                                <td data-label="@lang('Installment')">{{ $general->cur_sym }}{{ showAmount($savings->installment) }}</td>
                                @if($savings->status == 0)
                                    <td><span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span></td>
                                @else
                                    <td data-label="@lang('Installment Progress')">{{ $savings->installment_given }} / {{ $savings->total_installment }}</td>                              
                                @endif
                                <td data-label="@lang('Status')">
                                    @if($savings->status == 0)
                                        <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                    @elseif($savings->status == 1)
                                        <span class="text--small badge font-weight-normal badge--primary">@lang('Active')</span>
                                    @elseif($savings->status == 2)
                                        <span class="text--small badge font-weight-normal badge--success">@lang('Paid')</span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--danger">@lang('Close')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <button class="icon-btn  ml-1 details-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')"
                                        data-plan_name="{{ __($savings->savingsPlan->name) }}"
                                        data-interval="{{ installmentInterval($savings->installment_interval, $days) }}@lang(' Payable')"
                                        data-savings_amount="{{ getAmount($savings->savings_amount) }}"
                                        data-giveable_amount="{{ getAmount($savings->giveable_amount) }}"
                                        data-total_paid="{{  getAmount($savings->total_paid) }}"
                                        data-installment="{{ getAmount($savings->installment) }}"
                                        data-late_fee="{{ $savings->next_installment < now()->format('Y-m-d') ? getAmount($savings->late_fee) : 0 }}"
                                        data-installment_remaining="{{ $savings->total_installment - $savings->installment_given }}"
                                        data-installment_given="{{ $savings->installment_given }}"
                                        data-last_installment="{{ $savings->last_installment ? showDateTime($savings->last_installment, 'M-d, y') : '-' }}"
                                        data-next_installment="{{ $savings->status == 1 ? showDateTime($savings->next_installment, 'M-d, y') : '-' }}"
                                        >
                                        <i class="las la-desktop"></i>
                                    </button>
                                    <button class="icon-btn btn--success installment-btn"
                                        data-savings_id="{{ $savings->id }}"
                                        data-installment = "{{ getAmount($savings->installment) }}"
                                        data-late_fee = "{{ $savings->next_installment < now()->format('Y-m-d') ? getAmount($savings->late_fee) : 0 }}"
                                        {{ $savings->status != 1 ? 'disabled':'' }}
                                        >
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
                                      <form action="{{ route('user.savings.wallet.payment') }}" method="POST">
                                          @csrf
                                          <div class="modal-body text-center">
                                            <h5><span class="payAmount"></span> will be deducted from your wallet</h5>
                                            <br><br>
                                            <h5>Are you sure you want to continue ?</h5>
                                            <input type="hidden" name="savings_id" value="{{ $savings->id }}">
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel Payment</button>
                                            <button type="submit" class="btn btn-primary">Yes Make Payment</button>
                                          </div>
                                      </form>
                                      
                                    </div>
                                  </div>
                                </div>
                                
                                                            <!--PAY WITH CARD MODAL-->
                                <!-- Modal -->
                                <div class="modal fade" id="cardExampleModal" tabindex="-1" aria-labelledby="cardExampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="cardExampleModalLabel">Card Payment Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      
                                      @php
                                        $user = DB::table('users')->where('id', $savings->user_id)->first();
                                      @endphp
                                      
                                      <form id="makePaymentForm">
                                        @csrf
                                        <div class="modal-body text-center">
                                            <h5><span class="payAmount"></span> will be deducted from your Card</h5>
                                            <br><br>
                                            <h5>Are you sure you want to continue ?</h5>
                                            <input type="hidden" name="card_savings_id" value="{{ $savings->id }}">
                                            <div class="form-group d-none">
                                            <div class="row">
                                              <div class="col-6">
                                                <label for="name"></label>
                                                <input type="text" value="{{$user->firstname}} {{$user->lastname}}" name="cardName" id="payname" class="form-control" placeholder="Enter Your Name">
                                              </div>
                                              <div class="col-6">
                                                <label for="email"></label>
                                                <input type="text" value="{{$user->email}}" name="cardEmail" id="payemail" class="form-control" placeholder="Enter Your Email">
                                              </div>
                                    
                                              <div class="col-6">
                                                <label for="amount"></label>
                                                <input type="text" value="" name="cardAmount" id="payamount" class="form-control" placeholder="Enter The Amount">
                                              </div>
                                              <div class="col-6">
                                                <label for="mobile"></label>
                                                <input type="text" value="{{$user->mobile}}" name="cardMobile" id="paymobile" class="form-control" placeholder="Enter Your Mobile Number">
                                              </div>
                                            </div>
                                          </div>
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
            @if ($savingsList->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($savingsList) }}
                </div>
            @endif
        </div>
    </div>
</div>


<div id="savingsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Savings Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Savings Plan'):
                        <span class="savings-plan"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Savings Amount'):
                        <span class="savings-amount"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Receivable'):
                        <span class="total-receivable"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Paid')
                        <span class="total-paid"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Remaining Payable')
                        <span class="remaining-payble"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Installment Interval'):
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
                        @lang('Installment payable')
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
                <h5 class="modal-title">@lang('Savings Installment Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!--<form action="{{ route('user.savings.payment')}}" method="POST">-->

            <form id="makePaymentForm">
                @csrf
                <input type="hidden" name="savings_id">
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
                            <button id="payWithCard" data-toggle="modal" data-target="#cardExampleModal" type="button" class="btn btn--secondary btn-block">@lang('Pay With Card')</button>
                            
                    </div>
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
                var modal = $('#savingsModal');
                var data = $(this).data();
                var installment = parseFloat(data.installment);
                var lateFee = parseFloat(data.late_fee)

                modal.find('.savings-plan').text(data.plan_name);
                modal.find('.installment-interval').text(data.interval);
                modal.find('.savings-amount').text(curSym+data.savings_amount);
                modal.find('.total-receivable').text(curSym+data.giveable_amount);
                modal.find('.total-paid').text(curSym+data.total_paid);
                modal.find('.remaining-payble').text(curSym+(data.savings_amount-data.total_paid).toFixed(2));
                modal.find('.installment-remaining').text(data.installment_remaining);
                modal.find('.installment-given').text(data.installment_given);
                modal.find('.installment').text(curSym+installment);
                modal.find('.late-fee').text(curSym + lateFee.toFixed(2));
                modal.find('.installment-payable').text(curSym+(installment + lateFee).toFixed(2));
                modal.find('.last-installment').text(data.last_installment);
                modal.find('.next-installment').text(data.installment_remaining ? data.next_installment : '-');

                modal.modal('show');
            });

            $('.installment-btn').on('click', function(){
            
                var modal = $('#installmentModal');
                var data = $(this).data();
                var installment = parseFloat(data.installment);
                var lateFee = parseFloat(data.late_fee);

                $('[name=savings_id]').val(data.savings_id);
                $('.installment').text(curSym+installment.toFixed(2));
                $('.payAmount').text(curSym+installment.toFixed(2));
                $('[name=cardAmount]').val(installment.toFixed(2));
                $('.late-fee').text(curSym+lateFee.toFixed(2));
                $('.installment-payable').text(curSym+(installment+lateFee).toFixed(2))
                modal.modal('show');
            });
            // installmentModal
            
            // PAY FROM WALLET ACTION
            $('#payFromWallet').on('click', function(){
                $("#installmentModal").modal('hide');
            })
            
            // PAY WITH CARD ACTION
            $('#payWithCard').on('click', function(){
                $("#installmentModal").modal('hide');
            })

        })(jQuery);
    </script>
    
    <script>
    
        $(function () {
            $("#makePaymentForm").submit(function (e) {
              e.preventDefault();

              // COllect INPUT FIELD Data
              var name = $("#payname").val();
              var mobile = $("#paymobile").val();
              var email = $("#payemail").val();
              var amount = $("#payamount").val();
              
              // Make Payment
              makePayment(amount, email, mobile, name)
    
            })
          })
          
          
          
        function makePayment(amount, email, phone_number, name) {
            const modal = FlutterwaveCheckout({
              public_key: "FLWPUBK_TEST-95a4727476a81c116c4946e53a74977d-X",
              tx_ref: "RX1_{{ substr(rand(0, time()), 0, 7) }}",
              amount,
              currency: "NGN",
            //   country: "NG",
              payment_options: " ",
              customer: {
                email,
                phone_number,
                name,
              },
              callback: function (data) {
                var transaction_id = data.transaction_id;
                // console.log(transaction_id); 
                console.log(data);
                
                var savings_id = $("input[name='card_savings_id']").val();
                var currency = "NGN";
                var _token = $("input[name='_token']").val(); 
                $.ajax({
                  type: "POST",
                  url: "{{ URL::to('user/savings/card/payment') }}", 
                  data: {
                      transaction_id,  
                      amount,
                      currency,
                      savings_id,
                      _token 
                  },
                  success: function (response){
                    // console.log(response);
                    if(response.status == 200){
                        notify('success', 'Installment Taken Successfully');
                        modal.close();
                        // Send Notify Too
                        window.location.href = "active"; 
                    }else if(response.status == 400){
                        notify('error', 'Sorry, your payment cannot be processed now! Please, try again later');
                        modal.close();
                        window.location.href = "active"; 
                        
                    }
                  }
                })
              },
              onclose: function() {
                // close modal
              },
              customizations: {
                title: "Esusu Online",
                description: "Payment for Savings on Esusu Online",
                logo: "https://miro.medium.com/max/3150/1*Z1GByNW4KCR8HNCUjbgzdA.png",
              },
              
              
            });
        }

      // FLUTTERWAVE PAYMENT MODAL POPUP SCRIPT ENDS
          
            
    </script>
@endpush
