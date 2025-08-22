@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

    @include('components.breadcrumb')

    <div class="row">
        @if (session()->has('error'))
            <span class="invalid-feedback" role="alert">
                <strong> {{ session()->get('error') }}</strong>
            </span>
        @endif

        @if (session()->has('success'))
            <span class="invalid-feedback" role="alert">
                <strong> {{ session()->get('success') }}</strong>
            </span>
        @endif

        <div class="col-lg-8">
            <div class="card border card-border-primary">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Edit {{ $title }}</h5>
                    <div>
                        <a class="btn btn-dark btn-label waves-effect waves-light" href="{{ route('master.module') }}"><i class="ri-arrow-left-s-line label-icon align-middle fs-16 me-2"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form" class="form-horizontal form-bordered needs-validation" action="{{ route('master.module.update') }}" method="post" novalidate>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        @foreach($datas as $data)
                            <input type="hidden" name="id" value="{{ $data->module_id }}">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="name">Name <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $data->module_name }}" placeholder="name" required/>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="icon">Icon <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="icon" name="icon" value="{{ $data->module_icon }}" placeholder="Icon" required/>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="url">URL <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="url" name="url" value="{{ $data->module_url }}" placeholder="URL" required/>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="position">Position <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="position" name="position" value="{{ $data->module_position }}" placeholder="Position" required/>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="desc">Description</label>
                                        <textarea class="form-control" id="desc" name="desc" rows="8" style="resize: none;" placeholder="Description...">{{ $data->module_description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-lg-12">
                            <div class="text-end">
                                <button class="btn btn-success waves-effect waves-light">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection