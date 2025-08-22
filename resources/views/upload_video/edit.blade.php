@extends('layouts.master')

<!-- @section('title') {{ $title }} @endsection -->

@section('content')

@include('components.breadcrumb')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Satker</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form method="POST" action="{{ route('satkers.update', $detail->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="col-md-12 mb-4">
                        <label for="provinsi_id" class="form-label">Provinsi</label>
                        <input class="form-control" id="provinsi_id" name="provinsi_id" value="{{ $detail->provinsi_id }}" hidden/>
                        <input class="form-control" id="provinsi" name="provinsi" value="{{ $detail->provinsis->name }}" readonly/>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="satker" class="form-label">Nama Satker</label>
                        <input class="form-control" id="satker" name="satker" value="{{ $detail->name }}"/>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="kode_satker" class="form-label">Kode Satker</label>
                        <input class="form-control" id="kode_satker" name="kode_satker" value="{{ $detail->kode_satker }}"/>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="address" class="form-label">Alamat</label>
                        <input class="form-control" id="address" name="address" value="{{ $detail->address }}"/>
                    </div>
                    <button class="btn btn-success form-control">Ubah</button>
                </form>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
