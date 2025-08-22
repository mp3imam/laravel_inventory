@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ubah Layanan</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('layanans.update', $detail->id) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="col-md-12 mb-3">
                            <label for="layanan" class="form-label">Nama Layanan</label>
                            <input class="form-control" id="layanan" name="layanan" value="{{ $detail->name }}" />
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="kode" class="form-label">Kode</label>
                            <input class="form-control" id="kode" name="kode" pattern="[A-Za-z]{2}"
                                value="{{ $detail->kode }}" placeholder="Masukan 2 karakter Contoh: AB" />
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="colorPicker" class="form-label">Warna Kiosk</label>
                            <input type="color" class="form-control form-control-color w-100" name="colorPicker"
                                id="colorPicker" value="{{ $detail->color }}">
                            <span class="text-danger fs-12">Hindari warna terang, karena text kiosk berwarna putih</span>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input class="form-control" id="deskripsi" name="deskripsi" value="{{ $detail->deskripsi }}" />
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="icon" class="form-label">Pilih Icon Untuk Layanan</label><br>
                            @if ($detail->icon)
                                <img src="{{ $detail->icon }}" width="300px" class="mb-3" /><br>
                            @endif
                            <input type="file" name="icon" />
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
