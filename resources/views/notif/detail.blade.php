@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
@include('components.breadcrumb')

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="bg-soft-primary position-relative">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h3 class="fw-semibold">{{ $detail[0] }}</h3>
                        <p class="mb-0 text-muted">{{ $date }}</p>
                    </div>
                </div>
                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="1440" height="60" preserveAspectRatio="none" viewBox="0 0 1440 60">
                        <g mask="url(&quot;#SvgjsMask1001&quot;)" fill="none">
                            <path d="M 0,4 C 144,13 432,48 720,49 C 1008,50 1296,17 1440,9L1440 60L0 60z" style="fill: var(--vz-card-bg-custom);"></path>
                        </g>
                        <defs>
                            <mask id="SvgjsMask1001">
                                <rect width="1440" height="60" fill="#ffffff"></rect>
                            </mask>
                        </defs>
                    </svg>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="text-center">
                    <h6>{{ $detail[2] }}</h6>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
@endsection
