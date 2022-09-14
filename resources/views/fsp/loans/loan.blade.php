@extends('fsp.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">

                <div class="card-body p-0">
                    @if($fsp->cac_status == 0)
                        <div class="alert alert-danger alert-dismissible fade show text-center p-3" style="font-size:16px; background: #d9534f; color: white">
                          <strong>You have to Upload Your Company's CAC Documents before you can add a Loan Plan. Click <span><a href="{{ route('fsp.profile') }}" class="text-primary">here</a></span> to do that</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                              <span class="text-white" aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif($fsp->cac_status == 1)
                        <div class="alert alert-danger alert-dismissible fade show text-center p-3" style="font-size:16px; background: #d9534f; color: white">
                          <strong>Your company's document is still under review. Click <span><a href="{{ route('fsp.profile') }}" class="text-warning">here</a></span> to view the status</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                              <span class="text-white" aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif($fsp->cac_status == 2)
                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Loan Amount')</th>
                                    <th>@lang('Payable Amount')</th> 
                                    <th>@lang('Interval')</th>
                                    <th>@lang('Times')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($loanPlans as $plan)
                                <tr>
                                    <td data-label="@lang('S.N.')">{{ $loanPlans->firstItem() + $loop->index }}</td>
                                    <td data-label="@lang('Name')">{{ __($plan->name) }}</td>
                                    <td data-label="@lang('Loan Amount')">{{ $general->cur_sym }}{{ showAmount($plan->loan_amount) }}</td>
                                    <td data-label="@lang('Payable Amount')">{{ $general->cur_sym }}{{ showAmount($plan->payable_amount) }}</td>
                                    <td data-label="@lang('Interval')">{{ __(installmentInterval($plan->installment_interval, $days)) }}</td>
                                    <td data-label="@lang('Times')">{{ $plan->total_installment }}</td>
                                    <td data-label="@lang('Status')">
                                        @if($plan->status == 1)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Inactive')</span>
                                        @endif
                                    </td>
    
                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.plan.loan.edit', $plan->id) }}" class="icon-btn">
                                            <i class="la la-pencil"></i>
                                        </a>
                                        @if($plan->status == 0)
                                            <a data-toggle="modal" href="#statusModal" class="icon-btn bg--success ml-1 statusBtn" data-id="{{ $plan->id }}" data-name="{{__($plan->name)}}" data-status="0">
                                                <i class="la la-eye"></i>
                                            </a>
                                        @else
                                            <a data-toggle="modal" href="#statusModal" class="icon-btn bg--danger ml-1 statusBtn" data-id="{{ $plan->id }}" data-name="{{__($plan->name)}}" data-status="1">
                                                <i class="la la-eye-slash"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
    
                                </tbody>
                            </table><!-- table end -->
                        </div>
                    @endif
                </div>
                @if ($loanPlans->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loanPlans) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

{{-- STATUS METHOD MODAL --}}
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.plan.loan.status')}}" method="POST">
                @csrf
                <input type="hidden" name="plan_id">
                <div class="modal-body message">
                    <p>@lang('Are you sure to activate') <span class="font-weight-bold method-name"></span> @lang('method')?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@if($fsp->cac_status == 0 || $fsp->cac_status == 1)

@elseif($fsp->cac_status == 2)
    @push('breadcrumb-plugins')
    
    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
        <form action="" method="GET" class="header-search-form">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('Search Plan')" value="{{ request()->search }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        <a class="btn btn--primary box--shadow1 text--small" href="{{ route('fsp.plan.loan.create') }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
    </div>
    
    @endpush
@endif

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
