@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.signin')
@endsection
@section('content')
    <div class="auth-page-wrapper pt-5">
        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-2 text-white-50">
                            <div>
                                <a href="index" class="d-inline-block auth-logo">
                                    <img src="{{ URL::asset('assets/images/logo/logo.png') }}" alt=""
                                        height="100">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4 card-bg-fill">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">AIS</h5>
                                    <p class="text-muted">Application Information System</p>
                                </div>

                                @error('wrong')
                                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow  fade show mb-xl-0"
                                        role="alert">
                                        <i class="ri-error-warning-line label-icon"></i><strong>Oopps!</strong>
                                        - {{ $errors->first('wrong') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @enderror
                                @error('locked')
                                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow  fade show mb-xl-0"
                                        role="alert">
                                        <i class="ri-forbid-fill label-icon"></i><strong>Oopps!</strong>
                                        - {{ $errors->first('locked') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @enderror
                                @error('notfound')
                                    <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show"
                                        role="alert">
                                        <i class="ri-alert-line label-icon"></i><strong>Oopps!</strong>
                                        - {{ $errors->first('notfound') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @enderror
                                <div class="p-2 mt-4">
                                    <form action="{{ route('login.post') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text"
                                                class="form-control @if ($errors->first('email', ':message')) is-invalid @endif"
                                                id="email" name="email" placeholder="Enter email">
                                            @if ($errors->has('email'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email') }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-end">
                                            </div>
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password"
                                                    class="form-control pe-5 password-input @if ($errors->first('password', ':message')) is-invalid @endif"
                                                    name="password" placeholder="Enter password" id="password-input">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('password') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- {!! Geetest::render() !!} --}}
                                        <div class="mt-4">
                                            <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                        </div>

                                        <div class="mt-4 text-center">
                                        </div>
                                    </form>
                                    <form action="{{ route('guest.booking') }}">
                                        <div class="mt-4">
                                            <button class="btn btn-warning w-100" type="submit">Guest</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> AIS
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/js/pages/password-addon.init.js') }}"></script>
@endsection
