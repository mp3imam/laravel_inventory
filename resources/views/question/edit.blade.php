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
                    <form method="POST" action="{{ route('questions.update') }}">
                        @csrf
                        <div class="col-md-12 mb-3">
                            <input class="form-control" id="id" name="id" value="{{ $detail->id }}" hidden />
                            <label for="question" class="form-label">Pertanyaan</label>
                            <input class="form-control" id="question" name="question" value="{{ $detail->pertanyaan }}"
                                required />
                            @error('question')
                                <div class="text-danger">Masukan Pertanyaan</div>
                            @enderror
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
