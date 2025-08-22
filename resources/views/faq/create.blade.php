@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')
    @include('sweetalert::alert')

    <div class="row">
        <form action="{{ route('faq.store') }}" method="Post" class="needs-validation" novalidate>
            @csrf
            @method('POST')
            <div class="mb-3">
                <label for="pertanyaan" class="form-label">Pertanyaan</label>
                <input type="text" name="pertanyaan" class="form-control" placeholder="Masukan Pertanyaan" required>
            </div>
            <div class="mb-3">
                <label for="To" class="form-label">Jawaban</label><br>
                <textarea style="width:100%;" rows="10" name="jawaban" placeholder="Berikan tanda pipe (|) untuk enter" required></textarea>
            </div>
            <div>
                <a href="{{ route('faq.index') }}" class="btn btn-light">
                    <span><i class="ri-arrow-go-back-line align-bottom me-1"></i> Kembali</span>
                </a>
                <button type="submit" class="btn btn-success">
                    <span><i class="ri-save-3-line align-bottom me-1"></i> Simpan</span>
                </button>
            </div>
        </form>
    </div>
@endsection
