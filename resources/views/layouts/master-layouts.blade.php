<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal" data-layout-style=""
    data-layout-position="fixed" data-topbar="light">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} | KiosK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="author" content="{{ config('app.name') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo/favicon-kejaksaan.png') }}">
    @include('layouts.head-css')
</head>

<body style="background-color:#17594A; overflow:hidden;">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <header id="page-topbar" style="background-color:#17594A; border-bottom: none;">
            <div class="layout-width" style="max-width: 100%;">
                <div class="navbar-header" style="height: 100px;">
                    <div class="d-flex mt-2">
                        <!-- LOGO -->
                        <div class="navbar-brand-box horizontal-logo ">
                            <a href="{{ URL('/') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt=""
                                        height="100">
                                </span>
                                <span class="logo-lg mb-4">
                                    <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt=""
                                        width="90" height="90">
                                </span>
                            </a>

                            <a href="{{ URL('/') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt=""
                                        height="100">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt=""
                                        width="100" height="100">
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3" style="margin-right: auto;">
                        <div class="col-xl-10">
                            <div class="mb-0 fw-semibold lh-base h2 text-white text-uppercase">
                                Kejaksaan
                                Republik
                                Indonesia<h4 class="text-white mb-0">{{ $satker['name'] }}</h4>
                                <div>
                                    <h6 class="text-white">{{ $satker['address'] }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center">
                        {{-- <div class="col-md-3 text-end"> --}}
                        @php
                            switch (date('D')) {
                                case 'Mon':
                                    $hari = 'Senin';
                                    break;
                                case 'Tue':
                                    $hari = 'Selasa';
                                    break;
                                case 'Wed':
                                    $hari = 'Rabu';
                                    break;
                                case 'Thu':
                                    $hari = 'Kamis';
                                    break;
                                case 'Fri':
                                    $hari = 'Jumat';
                                    break;
                                case 'Sat':
                                    $hari = 'Sabtu';
                                    break;
                            
                                default:
                                    $hari = 'Minggu';
                                    break;
                            }
                        @endphp

                        <div id="dateTime" class="mt-3">
                            <span class="text-white h2" style="font-size:30px; margin-right:30px"
                                id="waktu">{{ date('H:i:s') }}</span><br>
                            <span class="text-white h4"
                                style="font-size:14px; margin-right:30px">{{ $hari . ', ' . date('d/m/Y') }}</span>
                        </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </header>


        @include('layouts.sidebar-kioska')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content" style="margin-left: 15px;">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid" style="max-width: 100%;">
                    @yield('content')
                </div> <!-- content -->
            </div>
            {{-- @include('layouts.footer') --}}
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.customizer')
    <!-- END Right Sidebar -->

    @include('layouts.vendor-scripts')
</body>

</html>
