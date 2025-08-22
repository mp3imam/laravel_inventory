@extends('layouts.master')

<!-- @section('title') {{ $title }} @endsection -->

@section('content')

@include('components.breadcrumb')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Edit Question</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form method="POST" action="{{ route('questions.update') }}">
                    @csrf
                    <div class="col-md-12 mb-4">
                        <label for="provinsi_id" class="form-label">Provinsi</label>
                        <input class="form-control" id="id" name="id" value="{{ $detail->id }}" hidden/>
                        <input class="form-control" id="provinsi_id" name="provinsi_id" value="{{ $detail->provinsi_id }}" hidden/>
                        <input class="form-control" id="provinsi" name="provinsi" value="{{ $detail->provinsis->name }}" readonly/>
                        @error('provinsi_id') <div class="text-danger">Pilih Provinsi</div> @enderror
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="satker_id" class="form-label">Satker</label>
                        <input class="form-control" id="satker_id" name="satker_id" value="{{ $detail->satker_id }}" hidden/>
                        <input class="form-control" id="satker" name="satker" value="{{ $detail->satkers->name }}" readonly/>
                        @error('satker_id') <div class="text-danger">Pilih Satker</div> @enderror
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="layanan_id" class="form-label">Layanan</label>
                        <input class="form-control" id="layanan_id" name="layanan_id" value="{{ $detail->layanan_id }}" hidden/>
                        <input class="form-control" id="layanan" name="layanan" value="{{ $detail->layanans->name }}" readonly/>
                        @error('layanan_id') <div class="text-danger">Pilih Layanan</div> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="question" class="form-label">Pertanyaan</label>
                        <input class="form-control" id="question" name="question" value="{{ $detail->pertanyaan }}" required/>
                        @error('question') <div class="text-danger">Masukan Pertanyaan</div> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="tanggal" class="form-label">Periode</label>
                        <select id='periode' name="periode" class="form-control" required>
                            <option value="">-- Pilih Semua --</option>
                            <option value="{{ \Carbon\Carbon::now()->format('Y') }}" {{ if($detail->periode == \Carbon\Carbon::now()->format('Y')) "selected" }}>{{ \Carbon\Carbon::now()->format('Y') }}</option>
                            <option value="{{ \Carbon\Carbon::now()->addYears(1)->format('Y') }}">{{ \Carbon\Carbon::now()->addYears(1)->format('Y') }} {{ if($detail->periode == \Carbon\Carbon::now()->addYears(1)->format('Y')) "selected" }}</option>
                            <option value="{{ \Carbon\Carbon::now()->addYears(2)->format('Y') }}">{{ \Carbon\Carbon::now()->addYears(2)->format('Y') }} {{ if($detail->periode == \Carbon\Carbon::now()->addYears(2)->format('Y')) "selected" }}</option>
                        </select>
                    @error('periode') <div class="text-danger">Masukan Periode</div> @enderror
                    </div>
                    <button class="btn btn-success form-control">Update</button>
                </form>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
