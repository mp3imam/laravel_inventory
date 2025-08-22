@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 mb-4">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Barang Dasar</option>
                                <option value="2">Barang Setengah Jadi</option>
                                <option value="3">Barang Jadi</option>
                                <option value="4">Barang Penolong</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input id="nama_barang" name="nama_barang" class="form-control"
                                placeholder="Masukan Nama Barang Baru" />
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="stock" class="form-label">Jumlah Stock</label>
                            <input id="stock" name="stock" type="number" min="1" class="form-control"
                                placeholder="Masukan Jumlah Stock" />
                        </div>
                </div>
                <button class="btn btn-success form-control"><i class="bx bxs-save label-icon align-middle fs-16 me-2"></i>
                    Simpan</button>
                </form>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
    </div>
    <!--end row-->
@endsection
