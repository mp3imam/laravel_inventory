@extends('layouts.master')

<!-- @section('title') {{ $title }} @endsection -->

@section('content')

@include('components.breadcrumb')

<div class="row">
    <div class="section-body">
        <form method="post" action="{{ url('rate_support_sistem') }}" enctype="multipart/form-data">
        @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Nama</label>
                        <input name="user_id" value="{{ $user->id }}" hidden>
                        <input name="username" value="{{ $user->name }}" class="form-control" readonly />
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label>Satker</label>
                          <input value="{{ $user->satker->id }}" name="satker_id" hidden/>
                          <input value="{{ $user->satker->name }}" name="satker" class="form-control" readonly />
                        </div>
                      </div>
                    </div>
                  <div class="form-group mb-3">
                    <label>Pertanyaan <sup class="text-danger">*</sup></label>
                    <textarea type="text" class="form-control" name="pertanyaan" required></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <label>Upload File (Optional)</label>
                    <input type="file" class="form-control" name="image"/>
                  </div>

                </div>
                <div class="card-footer text-right">
                  <button type="submit" name="add" class="btn btn-primary"><i class="far fa-save"></i> Simpan</button>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
            </div>

      </div>
    </form>
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
