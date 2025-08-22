@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Ubah Password</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form action="{{ route('password.update') }}" method="Post" class="needs-validation" novalidate>
                    @csrf
                    @method('POST')
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow  fade show mb-xl-0" role="alert">
                            <i class="ri-error-warning-line label-icon"></i>
                            <strong>
                            - {{ $error }} </strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div><br>
                        @endforeach
                    <div class="col-md-8 mb-3">
                        <label for="newpasswordInput" class="form-label">Password Lama*</label>
                        <div class="position-relative auth-pass-inputgroup mb-3">
                        <input type="password" name="password" class="form-control pe-5 password-input @if ($errors->first('password', ':message')) is-invalid @endif" id="password-input"
                            placeholder="Masukkan password lama" required>
                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>

                            @if ($errors->has('error'))
                            <div class="invalid-feedback">
                                {{ $errors->first('error') }}
                            </div>
                        @endif
                        </div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label for="newpasswordInput" class="form-label">Password Baru*</label>
                        <div class="position-relative auth-pass-inputgroup mb-3">
                        <input type="password" name="new_password" class="form-control pe-5 password-input @if ($errors->first('new_password', ':message')) is-invalid @endif" id="password-input"
                            placeholder="Masukkan password baru" required>
                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                        </div>

                    </div>
                    <!--end col-->
                    <div class="col-md-8 mb-3">
                        <label for="confirmpasswordInput" class="form-label">Konfirmasi Password*</label>
                        <div class="position-relative auth-pass-inputgroup mb-3">

                        <input type="password" name="password_confirmation" class="form-control pe-5 password-input @if ($errors->first('password_confirmation', ':message')) is-invalid @endif" id="password-input"
                            placeholder="Konfirmasi password" required>
                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                        </div>

                    </div>
                    <a href="{{ url('/') }}" class="btn btn-light">
                        <span><i class="ri-arrow-go-back-line align-bottom me-1"></i> Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-success">
                        <span><i class="ri-save-3-line align-bottom me-1"></i> Simpan</span>
                    </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/password-addon.init.js') }}"></script>
@endsection
