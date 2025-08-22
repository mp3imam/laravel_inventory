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
                    <form action="{{ route('questions.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="question" class="form-label">Pertanyaan</label>
                                <textarea id="question" name="question" class="form-control" placeholder="Masukan Pertanyaannya"></textarea>
                                @error('question')
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
