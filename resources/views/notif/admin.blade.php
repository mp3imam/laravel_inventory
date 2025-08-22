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
                <div class="card-header">
                    <h5>Admin</h5>
                </div><!-- end card header -->
                <form class="card-body" action="{{ route('notif.admin.update') }}" method="POST">
                    {{ csrf_field() }}
                    @method('POST')
                    <div class="row">
                        <label class="form-label mb-3">Atur Notifikasi :</label>
                        @foreach($menu as $value)
                        <div class="col-md-4">
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="menu[]" {{$value->status == 1 ? 'checked' : '' }} value="{{$value->id}}" role="switch" id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault">{{ $value->menu }}</label>
                            </div>
                        </div>
                        @endforeach

                        <div class="mb-3"></div>
                        <div class="col-lg-12">
                            <a href="{{ route('notif.index') }}" class="btn btn-secondary btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-arrow-go-back-line label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Kembali
                                    </div>
                                </div>
                            </a>
                            <button type="submit" class="btn btn-primary btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-file-edit-line label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Ubah
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
@endsection
