@extends('layouts.master')

<!-- @section('title') {{ $title }} @endsection -->

@section('content')

@include('components.breadcrumb')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Provinsi</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form method="POST" action="{{ route('provinsis.update', $detail->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="col-md-12 mb-3">
                        <label for="provinsi" class="form-label">Nama Provinsi</label>
                        <input class="form-control" id="provinsi" name="provinsi" value="{{ $detail->name }}"/>
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
