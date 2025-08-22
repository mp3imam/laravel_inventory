@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')
    @include('sweetalert::alert')
    <div class="row">
        <form action="{{ route('faq.update', $data->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="pertanyaan" class="form-label">Pertanyaan</label>
                <input type="text" name="pertanyaan" class="form-control" id="pertanyaan" value="{{ $data->question }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="To" class="form-label">Jawaban</label>
                <textarea style="width:100%;" rows="10" name="jawaban" placeholder="Berikan tanda pipe (|) untuk enter" required>{{ $data->answer }}</textarea>
            </div>
            <div>
                <a href="{{ route('faq.index') }}" class="btn btn-light">
                    <span><i class="ri-arrow-go-back-line align-bottom me-1"></i> Kembali</span>
                </a>
                <button type="submit" class="btn btn-success">
                    <span><i class="ri-save-3-line align-bottom me-1"></i> Ubah</span>
                </button>
            </div>
        </form>
    </div>
@endsection
