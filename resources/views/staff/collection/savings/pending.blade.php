@extends('staff.layouts.app')
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
                                <th>@lang('Username')</th>
                                <th>@lang('Savings Plan')</th>
                                <th>@lang('Installment Progress')</th>
                                <th>@lang('Last / Next Installment')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($pending)> 0)
                                @foreach($pending as $index => $row)
                                <tr>
                                    <td data-label="@lang('S.N.')">{{ $index++ + 1 }}</td>
                                    <td data-label="@lang('Username')">{{ $row->firstname }} {{ $row->lastname }}<br>
                                        @<span><a href="{{ route('admin.users.detail',$row->username) }}">{{ $row->username }}</a></span>
                                    </td>
                                    <td data-label="@lang('Username')">{{ ucwords($row->name) }}<br>
                                        <span><a href="#">{{ ucwords($row->savings_type) }} Plan</a></span>
                                    </td>
                                    <td data-label="@lang('Installment Progress')">{{ $row->installment_given }} / {{ $row->total_installment }}</td>
                                   
                                    <td data-label="@lang('Username')">
                                        <span class="text-danger">Next Pay: {{ date('d-m-Y', strtotime($row->next_payment)) }} 
                                            @if($row->next_payment == $today)
                                            <i class="ml-1 fas fa-exclamation-triangle"></i>
                                            @endif
                                        </span><br>
                                        <span>Last Paid:<span></span> {{ date('d-m-Y', strtotime($row->last_payment)) }}</span>
                                    </td>
                                    <td data-label="@lang('Status')">
                                        @if($row->status == 0)
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                        @elseif($row->status == 1)
                                            <span class="text--small badge font-weight-normal badge--primary">@lang('Active')</span>
                                        @elseif($row->status == 2)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Paid')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--danger">@lang('Close')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <button class="icon-btn  ml-1 details-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                            <i class="las la-desktop"></i>
                                        </button>
                                        <button class="icon-btn btn--success installment-btn" data-target="#exampleModal-{{ $row->id }}" data-toggle="modal" title="" data-original-title="@lang('Take Installment')">
                                            <i class="las la-comment-dollar"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal-{{ $row->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      
                                      <form action="{{ route('staff.user.savings.installment')}}" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ $row->id }}" name="savings_id">
                                          <div class="modal-body">
                                            <h6 class="mb-3">@lang('Is this installment taken')?</h6>
                                            <ul class="list-group">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    @lang('User Email')
                                                    <span class="user-email">{{ $row->email }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    @lang('Installment')
                                                    <span class="installment">&#8358; {{ number_format($row->installment, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    @lang('Late Fee')
                                                    <span class="late-fee">&#8358; {{ number_format($row->late_fee, 2) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    @lang('Installment Payable')
                                                    <span class="installment-payable">&#8358; {{ number_format($row->installment, 2) }}</span>
                                                </li>
                                            </ul>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Make Payment</button>
                                          </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($pending->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($pending) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')

<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <form action="" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('Username or Plan')" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

@endpush

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
        "use strict";
        (function($) {
            var curSym = `{{ $general->cur_sym }}`;
            

        })(jQuery);
    </script>
@endpush