@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ URL::asset('assets/images/bg-profile.jpg') }}" class="profile-wid-img" alt="">
            {{-- <div class="overlay-content">
                <div class="text-end p-3">
                </div>
            </div> --}}
        </div>
    </div>
<div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="@if (Auth::user()->avatar != '') {{ URL::asset('storage/profile/image/' . Auth::user()->avatar) }}@else{{ URL::asset('assets/images/users/avatar.png') }} @endif"
                                class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <form id="avatar-form"  action="{{ url('profile/avatar') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <input id="profile-img-file-input" name="avatar" type="file" class="profile-img-file-input avatar">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </form>

                        </div>
                        <h5 class="fs-16 mb-1">{{ $data->name }}</h5>
                        <p class="text-muted mb-0">{{ $data->getRoleNames()->first() }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">List Detail</h5>
                        </div>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-dark text-light">
                                <i class="ri-file-user-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control"
                            value="{{ $data->name }}" disabled>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-primary">
                                <i class="ri-mail-line"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" value="{{ $data->email }}" disabled>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-success">
                                <i class="ri-contacts-book-line"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control"value="{{ $data->nip }}" disabled>
                    </div>

                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-danger">
                                <i class=" ri-shield-user-line"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control"value="{{ $data->satker->name ?? '' }}" disabled>
                    </div>

                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-warning">
                                <i class="ri-map-pin-line"></i>
                            </span>
                        </div>
                        <textarea class="form-control" disabled>{{ $data->satker->address ?? '' }}</textarea>
                    </div>

                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist" id="nav-tab">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                Personal Details
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('profile.update') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="validationCustom03" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="validationCustom03"
                                                placeholder="Enter your email" value="{{ $data->email }}" required>
                                                <div class="invalid-feedback">
                                                    Email Tidak Boleh Kosong.
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="designationInput" class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control" id="designationInput"
                                                placeholder="Nama Lengkap" value="{{ $data->name }}" required>
                                                <div class="invalid-feedback">
                                                    Nama Tidak Boleh Kosong.
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nip</label>
                                            <input type="number" class="form-control"
                                                placeholder="Nip" name="nip" value="{{ $data->nip }}" required>
                                                <div class="invalid-feedback">
                                                    Nip Tidak Boleh Kosong.
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Updates</button>
                                            <!-- <button type="button" class="btn btn-soft-success">Cancel</button> -->
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>

@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
    <script>
        $(".avatar").change(function(){
            var form = $('#avatar-form')[0];
            var formData = new FormData(form);
            $.ajax({
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                url: "{{ url('profile/avatar') }}",
                success:function(data){
                    Swal.fire({
                                title: 'Berhasil!',
                                text: 'Anda Berhasil Mengganti Foto.',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            }).then(function(){
                                    location.reload();
                                });
                },

            });
        });
    </script>
@endsection
