@extends('fsp.layouts.app')

@section('panel')
    <form action="{{ route('fsp.profile.update') }}" method="POST" enctype="multipart/form-data">
        <div class="row mb-none-30">
            @csrf
            <div class="col-xl-3 col-lg-4 col-md-5 mb-30">
                <div class="card b-radius--5 overflow-hidden mb-4">
                    <div class="card-body p-0">
                        <div class="form-group">
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview"
                                            style="background-image: url({{ getImage(imagePath()['profile']['fsp']['path'] .'/' .auth()->guard('fsp')->user()->image,imagePath()['profile']['fsp']['size']) }})">
                                            <button type="button" class="remove-image"><i
                                                    class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit px-2">
                                        <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1"
                                            accept=".png, .jpg, .jpeg">
                                        <label for="profilePicUpload1" class="bg--success">@lang('Upload Profile
                                            Photo')</label>
                                        <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'),
                                                @lang('jpg').</b> @lang('Image will be resized into 400x400px') </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card b-radius--5 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="d-flex p-3 bg--primary align-items-center">
                            <div class="pl-3">
                                <h4 class="text--white">{{ __($fsp->fullname) }}</h4>
                            </div>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Name')
                                <span class="font-weight-bold">{{ __($fsp->fullname) }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Username')
                                <span class="font-weight-bold">{{ __($fsp->username) }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Email')
                                <span class="font-weight-bold">{{ $fsp->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Mobile')
                                <span class="font-weight-bold">{{ $fsp->mobile }}</span>
                            </li>
                        </ul>
                    </div>
                </div>


            </div>

            <div class="col-xl-9 col-lg-8 col-md-7 mb-30">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-50 border-bottom pb-2">@lang('Profile Information')</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')</label>
                                    <input class="form-control" type="text" name="firstname"
                                        value="{{ $fsp->firstname }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lastname"
                                        value="{{ $fsp->lastname }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Email')</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ $fsp->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile')</label>
                                    <input class="form-control" type="text" name="mobile"
                                        value="{{ $fsp->mobile }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Address')</label>
                                    <input class="form-control" type="text" name="address"
                                        value="{{ @$fsp->address->address }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('State')</label>
                                    <input class="form-control" type="text" name="state"
                                        value="{{ @$fsp->address->state }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Zip')</label>
                                    <input class="form-control" type="number" min="0" name="zip"
                                        value="{{ @$fsp->address->zip }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('City')</label>
                                    <input class="form-control" type="text" name="city"
                                        value="{{ @$fsp->address->city }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Country')</label>
                                    <input class="form-control" type="text" name="country"
                                        value="{{ @$fsp->address->country }}" disabled>
                                </div>
                            </div>
                            
                            @if($fsp->cac_docs == NULL)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Company\'s CAC Document')</label>
                                    <input class="form-control" type="file" name="cac_docs">
                                </div>
                            </div>
                            @endif
                            
                            @if($fsp->cac_status == 1)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Company\'s CAC Document Status')</label> <strong>:</strong>
                                    &nbsp;&nbsp;&nbsp;<span class="badge badge-warning px-3 py-1 text-dark font-weight-bold">Pending</span>
                                </div>
                            </div>
                            @endif                            
                            
                            @if($fsp->cac_status == 2)
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Company\'s CAC Document Status')</label> <strong>:</strong>
                                    &nbsp;&nbsp;&nbsp;<span class="badge badge-success px-3 py-1 text-white font-weight-bold">Verified <i class="fas fa-check"></i></span>
                                </div>
                            </div>
                            @endif
            
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('fsp.change.password') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i
            class="fa fa-key"></i>@lang('Change Password')</a>
@endpush

