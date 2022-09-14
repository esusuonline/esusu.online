@extends('fsp.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('fsp.partials.sidenav')
        @include('fsp.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                @include('fsp.partials.breadcrumb')

                @yield('panel')


            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>
@endsection

@push('style')
    <style>
     @media (max-width: 991px) {
        .fullscreen-btn{
            display: none;
        }
    }
    </style>
@endpush
