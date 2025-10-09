<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="gradient-4"
    data-sidebar-size="{{ isset($sidebar_collapse) && $sidebar_collapse ? 'sm' : 'lg' }}" data-sidebar-image="img-4" data-preloader="disable" data-theme="default"
    data-theme-colors="default">
<script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
    
<head>
    <meta charset="utf-8" />
    <title>Biro Klasifikasi Indonesia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Biro Klasifikasi Indonesia" name="description" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('images/favicon.png') }}">
    @include('layouts.head-css')
    <style>
        .grecaptcha-badge { 
            visibility: hidden !important;
        }
    </style>
</head>

@yield('body')

@yield('content')

@include('layouts.vendor-scripts')
</body>

</html>
