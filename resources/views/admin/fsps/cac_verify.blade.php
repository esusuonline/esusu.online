@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Fsp')</th>
                                <th>@lang('Email-Phone')</th>
                                <th>@lang('Country')</th>
                                <th>@lang('Joined At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($fsps as $fsp)
                            <tr>
                                <td data-label="@lang('Fsp')">
                                    <span class="font-weight-bold">{{$fsp->fullname}}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.fsps.detail', $fsp->id) }}"><span>@</span>{{ $fsp->username }}</a>
                                    </span>
                                </td>

                                <td data-label="@lang('Email-Phone')">
                                    {{ $fsp->email }}<br>{{ $fsp->mobile }}
                                </td>
                                <td data-label="@lang('Country')">
                                    <span class="font-weight-bold" data-toggle="tooltip" data-original-title="{{ @$fsp->address->country }}">{{ $fsp->country_code }}</span>
                                </td>


                                <td data-label="@lang('Joined At')">
                                    {{ showDateTime($fsp->created_at) }} <br> {{ diffForHumans($fsp->created_at) }}
                                </td>

                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.fsps.detail', $fsp->id) }}" class="icon-btn" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                        <i class="las la-desktop text--shadow"></i>
                                    </a>
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
                </div>
                @if ($fsps->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($fsps) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


@endsection

