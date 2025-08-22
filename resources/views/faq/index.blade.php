@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')
    @include('sweetalert::alert')
    <div class="row">
        <form action="{{ route('faq.index') }}">
            <div class="row">

                @if (auth()->user()->getRoleNames()->first() === 'super-admin')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a type="button" href="{{ route('faq.create') }}" class="btn btn-primary btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-mail-settings-line label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Tambah
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="row text-end">
                                <div class="col-md-8">
                                    <div class="form-icon right">
                                        <input type="text" class="form-control form-control-icon" name="pertanyaan"
                                            value="{{ Request::input('pertanyaan') }}"
                                            placeholder="Cari berdasarkan Pertanyaan">
                                        <i class="ri-search-line"></i>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-block btn-primary">
                                        <i class="ri-search-line"></i>
                                        Pencarian
                                        &nbsp;
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-9 mb-3 text-end">
                        <div class="form-icon right">
                            <input type="text" class="form-control form-control-icon" name="pertanyaan"
                                value="{{ Request::input('pertanyaan') }}" placeholder="Cari berdasarkan Pertanyaan">
                            <i class="ri-search-line"></i>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 text-end">
                        <button class="btn btn-block btn-primary">
                            <i class="ri-search-line"></i>
                            Pencarian
                            &nbsp;
                        </button>
                    </div>
                @endif
            </div>
        </form>

        <div class="d-flex align-items-center mb-3 mt-3">
            <div class="flex-grow-1">
                <h5 class="fs-16 mb-0 fw-semibold">Pertanyaan <i
                        class="ri-question-line fs-24 align-middle text-success me-1"></i>
                </h5>
            </div>
        </div>
        @if ($data->isEmpty())
            <div>
                <div class="alert alert-warning" role="alert">
                    <strong> Faq kosong </strong> silahkan klik tambah
                </div>
            </div>
        @endif
        @foreach ($data as $d)
            <div class="accordion accordion-border-box mb-2" id="genques-accordion">
                <div class="accordion-item">
                    @if (auth()->user()->getRoleNames()->first() === 'super-admin')
                        <div class="row bg-success">
                            <div class="col-md-11 bg-success">
                                <h2 class="accordion-header bg-success" id="genques-headingOne">
                                    <button class="accordion-button collapsed text-white bg-success" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#genques-collapseOne{{ $d['id'] }}"
                                        aria-expanded="false" aria-controls="genques-collapseOne{{ $d['id'] }}">
                                        {{ $d['question'] }}
                                    </button>
                                </h2>
                            </div>
                            <div class="col-md mt-2 bg-success">
                                <a class="align-middle text-black" data-toggle="reload"
                                    href="{{ route('faq.edit', $d['id']) }}">
                                    <i class="mdi mdi-pencil mdi-18px align-middle"></i>
                                </a>
                                <a class="align-middle ms-3 text-black" data-toggle="reload" href="#"
                                    onclick="ask_delete({{ $d['id'] . ',`' . $d['question'] }}`)">
                                    <i class="mdi mdi-delete mdi-18px align-middle"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <h2 class="accordion-header" id="genques-headingOne">
                            <button class="accordion-button collapsed bg-success text-white" type="button"
                                data-bs-toggle="collapse" data-bs-target="#genques-collapseOne{{ $d['id'] }}"
                                aria-expanded="false" aria-controls="genques-collapseOne{{ $d['id'] }}">
                                {{ $d['question'] }}
                            </button>
                        </h2>
                    @endif
                    @if ($d['answer'][1])
                        @foreach (explode('|', $d['answer']) as $answer)
                            <div id="genques-collapseOne{{ $d['id'] }}" class="m-2 accordion-collapse collapse show"
                                aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion" style="">
                                <i class="ri-checkbox-circle-fill text-success"></i> {{ $answer }}
                            </div>
                        @endforeach
                    @else
                        <div id="genques-collapseOne{{ $d['id'] }}" class="accordion-collapse collapse show"
                            aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion" style="">
                            <div class="accordion-body">
                                {{ $d['answer'] }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
    {!! $data->links('vendor.pagination.bootstrap-5') !!}
@endsection
@section('script')
    <script>
        function ask_delete(id, pertanyaan) {
            Swal.fire({
                    title: "Apakah Yakin Menghapus FAQ?",
                    text: pertanyaan,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    confirmButtonText: "Ya",
                    buttonsStyling: false,
                    showCloseButton: true
                })
                .then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "delete",
                            url: "{{ url('faq') }}" + '/' + id,
                            data: {
                                "_method": 'delete',
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                Swal.fire({
                                    title: 'Accepted!',
                                    text: 'Data Berhasil di Hapus',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
        }
    </script>
@endsection
