<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="gradient-4"
    data-sidebar-size="{{ isset($sidebar_collapse) && $sidebar_collapse ? 'sm' : 'lg' }}" data-sidebar-image="img-4" data-preloader="disable" data-theme="default"
    data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>Biro Klasifikasi Indonesia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Biro Klasifikasi Indonesia" name="description" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('images/favicon.png') }}">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/css/iziToast.min.css') }}">
    <!-- CSS Datatables -->
    <link href="{{ asset('assets/modules/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    @include('layouts.head-css')
    <style type="text/css">
      .logo-lg img {
        border-radius: 6px;
        height: 50px;
      }
      .logo-sm img {
        border-radius: 6px;
        height: 30px;
      }
      .header-profile-user {
/*        background-size: contain !important;*/
        background-size: cover !important;
        border-radius: 100px;
      }
      .grecaptcha-badge { 
        visibility: hidden !important;
      }
    </style>
<script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>

</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @include('layouts.alert')
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @include('layouts.customizer')

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
