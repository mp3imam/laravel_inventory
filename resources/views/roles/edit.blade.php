@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
            </div><!-- end card header -->
            <div class="card-body">
            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Role Name :</label>
                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                </div>

            </div>
                <div class="row">
                    <label class="form-label mb-3">Hak Akses :</label>
                    @foreach($permission as $value)
                        <div class="col-md-4">
                            <div class="form-check form-check-info mb-3">
                            {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'form-check-input','id' => 'formCheck8')) }}
                                <label class="form-check-label" for="formCheck8">
                                    {{ $value->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach

                    <div class="mb-3"></div>
                    <div class="col-lg-12">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-label">
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
            {!! Form::close() !!}


            </div>
        </div>
    </div>
</div>

@endsection
