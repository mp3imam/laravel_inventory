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
                    <h5 class="card-title mb-0 flex-grow-1">Add {{ $title }}</h5>
                    <div>
                        <a class="btn btn-dark btn-label waves-effect waves-light" href="{{ route('master.module') }}"><i class="ri-arrow-left-s-line label-icon align-middle fs-16 me-2"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form" class="form-horizontal form-bordered needs-validation" action="{{ route('master.module.store') }}" method="post" novalidate>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="name" required/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="icon">Icon <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon" required/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="url">URL <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="url" name="url" placeholder="URL" value="#" required/>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="url">Parent</label>
                                    <select class="form-select" id="parent" name="parent">
                                        <option value="0" selected>---</option>
                                        <option value="1">Core</option>
                                        <option value="2">Settings</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="position">Position <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" id="position" name="position" placeholder="Position" required/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="desc">Description</label>
                                    <textarea class="form-control" id="desc" name="desc" rows="8" style="resize: none;" placeholder="Description..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="text-end">
                                <button class="btn btn-success waves-effect waves-light">Submit</button>
                                <button type="reset" class="btn btn-light waves-effect waves-light">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection