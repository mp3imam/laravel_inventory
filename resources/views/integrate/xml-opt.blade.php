@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
@include('components.breadcrumb')

    <div class="row">
        <div class="col-xl-6 mb-3">
            @if(Session::has('message'))
            <div class="alert alert-danger alert-dismissible border-2 bg-body-secondary fade show mb-xl-0" role="alert">
                <strong>{{ Session::get('message') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Eksport XML</h5>
                </div><!-- end card header -->
                <form class="card-body" action="{{ route('integrate.xml') }}" method="POST">
                    {{ csrf_field() }}
                    @method('POST')
                    <div class="row">

                        <label class="form-label mb-3">Pilih Data untuk dieksport XML :</label>
                        <div class="col-md-4">
                            <div class="form-check form-check-success mb-3">
                                <input class="form-check-input" name="id" value="id" type="checkbox" id="formCheck8">
                                <label class="form-check-label" for="formCheck8">
                                    ID
                                </label>
                            </div>
                            <div class="form-check form-check-success mb-3">
                                <input class="form-check-input" name="kode_satker" value="kode_satker" type="checkbox" id="formCheck8">
                                <label class="form-check-label" for="formCheck8">
                                    Kode
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-check-success mb-3">
                                <input class="form-check-input" name="name" value="name" type="checkbox" id="formCheck8">
                                <label class="form-check-label" for="formCheck8">
                                    Nama
                                </label>
                            </div>
                            <div class="form-check form-check-success mb-3">
                                <input class="form-check-input" name="provinsi_id" value="provinsi_id" type="checkbox" id="formCheck8">
                                <label class="form-check-label" for="formCheck8">
                                    Provinsi
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-check-success mb-3">
                                <input class="form-check-input" name="address" value="address" type="checkbox" id="formCheck8">
                                <label class="form-check-label" for="formCheck8">
                                    Alamat
                                </label>
                            </div>
                        </div>

                        <div class="mb-3"></div>
                        <div class="col-lg-12">
                            <a href="{{ route('notif.index') }}" class="btn btn-secondary btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-arrow-go-back-line label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Kembali
                                    </div>
                                </div>
                            </a>
                            <button type="submit" class="btn btn-success btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-file-edit-line label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Buat
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
