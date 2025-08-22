@extends('layouts.master')

<!-- @section('title') {{ $title }} @endsection -->

@section('content')

@include('components.breadcrumb')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Provinsi</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <form action="{{ route('provinsis.store') }}" method="POST">
                    @csrf
                    <div class="col-md-12 mb-4">
                        <label for="validationCustom01" class="form-label">Provinsi</label>
                        <input id="provinsi" name="provinsi" class="form-control" placeholder="Masukan Nama Provinsi Baru"/>
                    </div>
                    <button class="btn btn-success form-control"><i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan</button>
                </form>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
