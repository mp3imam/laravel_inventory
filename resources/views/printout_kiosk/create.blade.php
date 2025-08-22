@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')
    @include('sweetalert::alert')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('printout_kiosk.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="bawah" class="form-label">Masukan Ucapan Cetak Kiosk</label>
                                <textarea id="bawah" name="bawah" class="form-control" placeholder="Masukan Ucapan Cetak Kiosk"></textarea>
                                @error('bawah')
                                    <div class="text-danger">Masukan Pertanyaan</div>
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-success form-control"><i
                                class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan</button>
                    </form>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
    <!--end row-->
@endsection
