@extends('user.layouts.master')
@php
    $policyPages = getContent('policy_pages.element');
@endphp

@section('content')
<div class="page-wrapper default-version">
    <div class="form-area bg_img" data-background="{{ asset('assets/admin/images/1.jpg') }}">
        <div class="form-wrapper">
            <h4 class="logo-text mb-15">@lang('Welcome to') <strong>{{ __($general->sitename) }}</strong></h4>
            <p>{{ __($pageTitle) }} @lang('to') {{ __($general->sitename) }} @lang('dashboard')</p>
            
            <div id="user_bvn_checker" class="row mt-30">
                
                <form id="user_bvn_form" class="account--form cmn-form mt-30">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <label for="firstname"><h5>@lang('User Registration')</h5></label>
                            </div>
                        </div>
                        
                        <div class="col-12" id="user_bvn_prompt_div">
                            <div class="form-group">
                                <label for="user_bvn_prompt"><h5>@lang('Do you have a BVN?')</h5></label>
                                <select name="user_bvn_prompt" class="form-control" id="user_bvn_prompt" required>
                                    <option selected value="">Select an option</option>
                                    <option value="yes">YES</option>
                                    <option value="no">NO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row" id="user_bvn_div" style="display: none">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fsp_bvn">@lang('Enter BVN')</label>
                                    <input type="text" name="fsp_bvn" class="form-control " id="fsp_bvn" value="{{ old('fsp_bvn') }}" placeholder="@lang('Enter your BVN')" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button id="check_fsp_bvn_btn" type="submit" class="submit-btn mt-25">@lang('Validate BVN') <i class="las la-sign-in-alt"></i></button>
                                </div>
                            </div>
                        </div>
                    
                    
                </form>
            </div>
            
            <div id="user_reg_placeholder" style="display: none" class="row">

                <div class="ph-item col-lg-12 col-sm-12">
                    <div class="ph-col-12">
                        <div class="ph-picture"></div>
                        <div class="ph-row">
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                            <div class="ph-col-6"></div>
                        </div>
                    </div>
                </div>

            </div>
            
            <form id="user_reg_form" style="display: none" action="{{ route('user.register') }}" method="POST" class="account--form cmn-form mt-30">
                @csrf
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstname">@lang('Firstname')</label>
                            <input type="text" name="firstname" class="form-control " id="firstname" value="{{ old('firstname') }}" placeholder="@lang('Enter your firstname')" required>
    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lastname">@lang('Lastname')</label>
                            <input type="text" name="lastname" class="form-control " id="lastname" value="{{ old('lastname') }}" placeholder="@lang('Enter your lastname')" required>
                        </div>
                    </div>
    
                    <div class="form-group col-md-6">
                        <label for="username">@lang('Username')</label>
                        <input type="text" name="username" class="form-control  checkUser" id="username" value="{{ old('username') }}" placeholder="@lang('Enter your username')" required>
                        <small class="text-danger usernameExist"></small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">@lang('Email')</label>
                        <input type="email" name="email" class="form-control checkUser" id="email" value="{{ old('email') }}" placeholder="@lang('Enter your email')" required>
                    </div>                    
                    <div style="display: none" class="form-group col-md-6">
                        <label for="bvn">@lang('BVN')</label>
                        <input type="number" name="bvn" class="form-control checkUser" id="bvn" value="{{ old('bvn') }}" placeholder="@lang('Enter your BVN')">
                    </div>
                    
                    
                    
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">@lang('Mobile')</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text mobile-code"></span>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                </div>
                                <input type="number" name="mobile" class="form-control checkUser" id="mobile" value="{{ old('mobile') }}" placeholder="@lang('Enter your mobile')" required>
                                <small class="text-danger mobileExist"></small>
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="alt_mobile">@lang('Alternative Number')</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text mobile-code"></span>
                                    <input type="hidden" name="mobile_code">
                                    <input type="hidden" name="country_code">
                                </div>
                                <input type="number" name="alt_mobile" class="form-control checkUser" id="alt_mobile" value="{{ old('alt_mobile') }}" placeholder="@lang('Enter your Altenative Mobile')" required>
                                <small class="text-danger mobileExist"></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">@lang('Country')</label>
                            <select name="country" class="form-control" id="country" required>
                                @foreach($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}"> {{ __($country->country) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div style="display: none" class="col-md-6">
                        <div class="form-group">
                            <label for="dob">@lang('Date Of Birth')</label>
                            <input type="text" name="dob" class="form-control " id="dob" value="{{ old('dob') }}" placeholder="@lang('Enter your DOB')">

                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_type">@lang('Select A Savings Account Type?')</label>
                            <select name="account_type" class="form-control" id="account_type" required>
                                <option selected value="">Select an Account Type</option>
                                <option value="business">Business</option>
                                <option value="individual">Individual</option>
                            </select>
                        </div>
                    </div>
                    
                    
                    
                    <div class="col-md-6">
                        <div class="form-group hover-input-popup">
                            <label>@lang('Password') <sup class="text--danger">*</sup></label>
                            <input type="password" id="password" name="password" placeholder="@lang('Create your password')" class="form-control" required>
                            @if($general->secure_password)
                                <div class="input-popup">
                                <p class="error lower">@lang('1 small letter minimum')</p>
                                <p class="error capital">@lang('1 capital letter minimum')</p>
                                <p class="error number">@lang('1 number minimum')</p>
                                <p class="error special">@lang('1 special character minimum')</p>
                                <p class="error minimum">@lang('6 character password')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Confirm Password') <sup class="text--danger">*</sup></label>
                            <input type="password" name="password_confirmation" placeholder="@lang('Retype your password')" class="form-control" required>
                        </div>
                    </div>                

                    
                </div>
                

                    <div class="form-group">
                        @php echo loadReCaptcha() @endphp
                    </div>
    
                    @include('partials.custom_captcha')
    
                    @if($general->agree)
                    <div class="form-group">
                        <input type="checkbox" id="agree" name="agree">
                        <label for="agree" class="ms-1">@lang('I agree with')
                            @foreach ($policyPages as $policyPage)
                                <a href="{{ route('policy', [$policyPage, slug($policyPage->data_values->title)]) }}" target="_blank">
                                    {{ __($policyPage->data_values->title) }}@if(!$loop->last), @endif
                                </a>
                            @endforeach
                        </label>
                    </div>
                    @endif
    
                    <div class="form-group">
                        <button type="submit" class="submit-btn mt-25">@lang('Register') <i class="las la-sign-in-alt"></i></button>
                    </div>
            </form>
            <div class="text-center">
                <span class="text-dark">@lang('Already have an account?')</span> <a href="{{ route('user.login') }}">@lang('Login now.')</a>
            </div>
        </div>
    </div><!-- login-area end -->
</div>

{{-- Exists Modal --}}
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>

          </button>
        </div>
        <div class="modal-body">
          <h6 class="text-center">@lang('You already have an account please Sign in ')</h6>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn--danger" data-dismiss="modal">@lang('Close')</button>
          <a href="{{ route('user.login') }}" class="btn btn--primary">@lang('Login')</a>
        </div>
      </div>
    </div>
</div>
@endsection

@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush

@push('style')
    <style>
        .form-area .form-wrapper {
            width: 630px;
        }
    </style>
@endpush

@push('script')
    <script>
      "use strict";
      function submitUserForm() {
          var response = grecaptcha.getResponse();
          if (response.length == 0) {
              document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
              return false;
          }
          return true;
      }
      (function ($) {
            @if($mobile_code)
                $(`option[data-code={{ $mobile_code }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));

            @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }

                $.post(url,data,function(response) {
                    if (response['data'] && response['type'] == 'email') {
                        $('#existModalCenter').modal('show');
                    }else if(response['data'] != null){
                        $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                    }else{
                        $(`.${response['type']}Exist`).text('');
                    }
                });
            });
            
            // SHOW BVN OR NOT
            
            $(document).on("change", "#user_bvn_prompt", function (e) {
                let ans = $(this).val();
                
                if(ans == "yes"){
                    $("#user_bvn_div").show();
                    $("#user_bvn_prompt_div").hide();
                }else if(ans == "no"){
                    $("#user_reg_form").show();
                    $("#user_bvn_prompt_div").hide();
                    
                }
            });
            
            // CHECK BVN
            $(document).on("submit", "#user_bvn_form", function (e) {
                
                e.preventDefault();
                
                $("#user_reg_placeholder").show();
                
                var formData = new FormData($("#user_bvn_form")[0]);
                $.ajax({
                    type: "POST",
                    url: "check-user-bvn",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $("#user_reg_placeholder").hide();
                        
                        // console.log(response.message.ResponseCode);
                        
                        if(response.message.ResponseCode == "00"){
                            $("#firstname").val(response.message.FirstName);
                            $("#firstname").attr('readonly', true);
                            
                            $("#lastname").val(response.message.LastName);
                            $("#lastname").attr('readonly', true);
                            
                            $("#bvn").val(response.message.BVN);
                            
                            $("#mobile").val(response.message.PhoneNumber1);
                            $("#mobile").attr('readonly', true);
                            
                            $("#dob").val(response.message.DateOfBirth);
                            $("#dob").attr('readonly', true);
                            
                            
                            $("#user_reg_form").show();
                            $("#user_bvn_checker").hide();
                        }else{
                            $("#user_bvn_form").html("<p>OOps! Something Went Wrong!</p>")
                        }
                    },
                    error: function () {
                        console.log("Error");
                    },
                });
            });

        })(jQuery);

    </script>
@endpush
