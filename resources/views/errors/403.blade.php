@extends('layouts.master-without-nav')

@section('title')
@lang('translation.Error_404')
@endsection

@section('body')
<body>
@endsection
@section('content')
    <div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">

    <!-- auth-page content -->
    <div class="auth-page-content overflow-hidden p-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/403.png') }}" alt="error img" width="70%" class="img-fluid">
                        <div class="mt-3">
                            <h3 class="text-uppercase">Sorry, Forbidden Page !</h3>
                            <p class="text-muted mb-4">The page you are not access !</p>
                            <a href="/" class="btn btn-primary"><i class="mdi mdi-home me-1"></i>Back to home</a>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth-page content -->
    </div>
        <!-- end auth-page-wrapper -->

@endsection
