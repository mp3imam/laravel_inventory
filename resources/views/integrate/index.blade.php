@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Link File XML</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">

                    <div class="text-center">
                        <a href="{{ route('integrate.xml-opt') }}">
                            <img src="{{ URL::asset('assets/images/xml.png') }}" alt="" width="25%" height="20%">
                        </a>
                    </div>

                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">API Documentation</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="text-center">
                        <a href="{{URL::to('/api/docs')}}">
                            <img src="{{ URL::asset('assets/images/api.png') }}" alt="" width="25%" height="20%">
                        </a>
                    </div>

                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
