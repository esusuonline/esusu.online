@extends('user.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('Savings Plan Information')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Plan Name')
                            <span>{{ $plan->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Savings Amount')
                            <span>{{ $general->cur_sym.showAmount($plan->savings_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Receivable Amount')
                            <span>{{ $general->cur_sym.showAmount($plan->giveable_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Total Installments')
                            <span>{{ $plan->total_installment }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Savings per Installment')
                            <span>{{ $general->cur_sym.showAmount($plan->installment) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Installment Interval')
                            <span>{{ ucwords($plan->savings_type) }} Savings</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Late Fee')
                            <span> {{ $general->cur_sym.showAmount($plan->fixed_late_fee + ($plan->installment * $plan->percent_late_fee / 100)) }}</span>
                        </li>
                        <li class="list-group-item">
                           @lang('Description') <hr class="mt-1 mb-2">
                           @php
                               echo $plan->description;
                           @endphp
                        </li>
                      </ul>
                </div>
            </div>
        </div>
                        
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('Required Information')</h5>
                </div>
                <form action="{{ route('user.savings.apply', $plan->id) }}" method="POST" enctype="multipart/form-data">
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
                                        <input class="form-control file-input-custom" type="file" name="{{$k}}" accept="image/*" @if($v->validation == "required") required @endif>
                                       
                                        @if ($errors->has($k))
                                            <br>
                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        
                        @else
                            <p>No additional Information is required for this plan</p>
                        @endif
                    </div>
                    
                    @php
                        $check = DB::table('savings')->where('savings_plan_id', $plan->id)->where('user_id', Auth::user()->id)->first();
                    @endphp
                        
                    <div class="card-footer">
                        <div class="form-group">
                            @if(Auth::user()->withdrawable_funds == 0)
                                <button type="submit" class="btn btn--danger btn-block text-white">
                                    @lang('You must fund your account before you start Saving') 
                                <br> @lang('Click <span class="text-warning font-weight-bold">HERE</span> to fund account')
                                </button>
                            @else
                                @if(!$check)
                                    <button type="submit" class="btn btn--primary btn-block">@lang('Apply For Savings')</button>
                                @elseif($check->status == 0)
                                    <button type="button" disabled class="btn btn--warning btn-block">@lang('Applied : (Pending)')</button>
                                @elseif($check->status == 1)
                                    <button type="button" disabled class="btn btn--secondary btn-block">@lang('Activated')</button>
                                 @elseif($check->status == 2)
                                    <button type="submit" class="btn btn--primary btn-block">@lang('Apply For Savings')</button>
                                @elseif($check->status == 3)
                                    <button type="submit" class="btn btn--primary btn-block">@lang('Apply For Savings')</button>
                                @endif
                            @endif
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('user.savings.plan') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control" required />  
                        <div class="input-group-append">
                            <span class="input-group-text icon-btn btn--danger support-btn remove-btn border-0"><i class="fa fa-times"></i></span>
                        </div>                     
                    </div>
                `)
            });
            $(document).on('click','.remove-btn',function(){
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
