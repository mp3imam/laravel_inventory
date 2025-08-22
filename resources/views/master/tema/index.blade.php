@extends('layouts.master')

@section('title') {{$title}} @endsection

@section('content')

    @include('components.breadcrumb')

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-height-100">
                <div class="card-body">
                    <div class="offcanvas-body p-0">
                        <div data-simplebar class="h-100">
                            <div class="p-2">
                                <!--preloader-->
                                <div id="preloader">
                                    <div id="status">
                                        <div class="spinner-border text-primary avatar-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="mb-2 fw-semibold text-uppercase">Color Scheme</h6>
                                <div class="colorscheme-cardradio">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check card-radio tes">
                                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-light" value="light">
                                                <label class="form-check-label p-0 avatar-md w-100" for="layout-mode-light">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Light</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check card-radio tes2 dark">
                                                <input class="form-check-input" type="radio" name="data-layout-mode" id="layout-mode-dark" value="dark">
                                                <label class="form-check-label p-0 avatar-md w-100 bg-dark" for="layout-mode-dark">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-soft-light d-flex h-100 flex-column gap-1 p-1">
                                                                <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-soft-light d-block p-1"></span>
                                                                <span class="bg-soft-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                                        </div>
                                    </div>
                                </div>

                                <div id="layout-position">
                                    <h6 class="mt-4 mb-2 fw-semibold text-uppercase">Layout Position</h6>

                                    <div class="btn-group radio" role="group">
                                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-fixed" value="fixed">
                                        <label class="btn btn-light w-sm" for="layout-position-fixed">Fixed</label>

                                        <input type="radio" class="btn-check" name="data-layout-position" id="layout-position-scrollable" value="scrollable">
                                        <label class="btn btn-light w-sm ms-0" for="layout-position-scrollable">Scrollable</label>
                                    </div>
                                </div>
                                <h6 class="mt-4 mb-2 fw-semibold text-uppercase">Header Color</h6>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-check card-radio">
                                            <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-light" value="light">
                                            <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-light">
                                                <span class="d-flex gap-1 h-100">
                                                    <span class="flex-shrink-0">
                                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                            <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        </span>
                                                    </span>
                                                    <span class="flex-grow-1">
                                                        <span class="d-flex h-100 flex-column">
                                                            <span class="bg-light d-block p-1"></span>
                                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                        <h5 class="fs-13 text-center mt-2">Light</h5>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check card-radio">
                                            <input class="form-check-input" type="radio" name="data-topbar" id="topbar-color-dark" value="dark">
                                            <label class="form-check-label p-0 avatar-md w-100" for="topbar-color-dark">
                                                <span class="d-flex gap-1 h-100">
                                                    <span class="flex-shrink-0">
                                                        <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                            <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                        </span>
                                                    </span>
                                                    <span class="flex-grow-1">
                                                        <span class="d-flex h-100 flex-column">
                                                            <span class="bg-primary d-block p-1"></span>
                                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                        <h5 class="fs-13 text-center mt-2">Dark</h5>
                                    </div>
                                </div>

                                <div id="sidebar-size">
                                    <h6 class="mt-4 mb-2 fw-semibold text-uppercase">Sidebar Size</h6>

                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-default" value="lg">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-default">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Default</h5>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-compact" value="md">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-compact">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                                <span class="d-block p-1 bg-soft-primary rounded mb-2"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Compact</h5>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small" value="sm">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-light d-flex h-100 flex-column gap-1">
                                                                <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Small Icon</h5>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar-size" id="sidebar-size-small-hover" value="sm-hover">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-size-small-hover">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-light d-flex h-100 flex-column gap-1">
                                                                <span class="d-block p-1 bg-soft-primary mb-2"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 pb-0 bg-soft-primary"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Icon Hover</h5>
                                        </div>
                                    </div>
                                </div>

                                <div id="sidebar-view">
                                    <h6 class="mt-4 mb-2 fw-semibold text-uppercase">Sidebar View</h6>

                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-default" value="default">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-default">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Default</h5>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-layout-style" id="sidebar-view-detached" value="detached">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-view-detached">
                                                    <span class="d-flex h-100 flex-column">
                                                        <span class="bg-light d-flex p-1 gap-1 align-items-center px-2">
                                                            <span class="d-block p-1 bg-soft-primary rounded me-1"></span>
                                                            <span class="d-block p-1 pb-0 px-2 bg-soft-primary ms-auto"></span>
                                                            <span class="d-block p-1 pb-0 px-2 bg-soft-primary"></span>
                                                        </span>
                                                        <span class="d-flex gap-1 h-100 p-1 px-2">
                                                            <span class="flex-shrink-0">
                                                                <span class="bg-light d-flex h-100 flex-column gap-1 p-1">
                                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                    <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                </span>
                                                            </span>
                                                        </span>
                                                        <span class="bg-light d-block p-1 mt-auto px-2"></span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Detached</h5>
                                        </div>
                                    </div>
                                </div>
                                <div id="sidebar-color">
                                    <h6 class="mt-4 mb-2 fw-semibold text-uppercase">Sidebar Color</h6>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-light" value="light">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-light">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-white border-end d-flex h-100 flex-column gap-1 p-1">
                                                                <span class="d-block p-1 px-2 bg-soft-primary rounded mb-2"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-primary"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Light</h5>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-check sidebar-setting card-radio" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient.show">
                                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-dark" value="dark">
                                                <label class="form-check-label p-0 avatar-md w-100" for="sidebar-color-dark">
                                                    <span class="d-flex gap-1 h-100">
                                                        <span class="flex-shrink-0">
                                                            <span class="bg-primary d-flex h-100 flex-column gap-1 p-1">
                                                                <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                                <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                            </span>
                                                        </span>
                                                        <span class="flex-grow-1">
                                                            <span class="d-flex h-100 flex-column">
                                                                <span class="bg-light d-block p-1"></span>
                                                                <span class="bg-light d-block p-1 mt-auto"></span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </label>
                                            </div>
                                            <h5 class="fs-13 text-center mt-2">Dark</h5>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn btn-link avatar-md w-100 p-0 overflow-hidden border collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBgGradient" aria-expanded="false" aria-controls="collapseBgGradient">
                                                <span class="d-flex gap-1 h-100">
                                                    <span class="flex-shrink-0">
                                                        <span class="bg-vertical-gradient d-flex h-100 flex-column gap-1 p-1">
                                                            <span class="d-block p-1 px-2 bg-soft-light rounded mb-2"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                            <span class="d-block p-1 px-2 pb-0 bg-soft-light"></span>
                                                        </span>
                                                    </span>
                                                    <span class="flex-grow-1">
                                                        <span class="d-flex h-100 flex-column">
                                                            <span class="bg-light d-block p-1"></span>
                                                            <span class="bg-light d-block p-1 mt-auto"></span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </button>
                                            <h5 class="fs-13 text-center mt-2">Gradient</h5>
                                        </div>
                                    </div>
                                    <!-- end row -->

                                    <div class="collapse" id="collapseBgGradient">
                                        <div class="d-flex gap-2 flex-wrap img-switch p-2 px-3 bg-light rounded">

                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient" value="gradient">
                                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient">
                                                    <span class="avatar-title rounded-circle bg-vertical-gradient"></span>
                                                </label>
                                            </div>
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-2" value="gradient-2">
                                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-2">
                                                    <span class="avatar-title rounded-circle bg-vertical-gradient-2"></span>
                                                </label>
                                            </div>
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-3" value="gradient-3">
                                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-3">
                                                    <span class="avatar-title rounded-circle bg-vertical-gradient-3"></span>
                                                </label>
                                            </div>
                                            <div class="form-check sidebar-setting card-radio">
                                                <input class="form-check-input" type="radio" name="data-sidebar" id="sidebar-color-gradient-4" value="gradient-4">
                                                <label class="form-check-label p-0 avatar-xs rounded-circle" for="sidebar-color-gradient-4">
                                                    <span class="avatar-title rounded-circle bg-vertical-gradient-4"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="sidebar-img">
                                    <h6 class="mt-4 mb-2 fw-semibold text-uppercase">Sidebar Image</h6>
                                    <div class="d-flex gap-2 flex-wrap img-switch">
                                        <div class="form-check sidebar-setting card-radio">
                                            <input class="form-check-input" type="radio" name="data-sidebar-image" id="sidebarimg-none" value="none">
                                            <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-none">
                                                <span class="avatar-md w-auto bg-light d-flex align-items-center justify-content-center">
                                                    <i class="ri-close-fill fs-20"></i>
                                                </span>
                                            </label>
                                        </div>

                                        <div class="form-check sidebar-setting card-radio">
                                            <input class="form-check-input" type="radio" name="data-sidebar-image" id="sidebarimg-01" value="img-1">
                                            <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-01">
                                                <img src="{{URL::asset('assets/images/sidebar/img-1.jpg')}}" class="avatar-md w-auto object-cover">
                                            </label>
                                        </div>

                                        <div class="form-check sidebar-setting card-radio">
                                            <input class="form-check-input" type="radio" name="data-sidebar-image" id="sidebarimg-02" value="img-2">
                                            <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-02">
                                                <img src="{{URL::asset('assets/images/sidebar/img-2.jpg')}}" class="avatar-md w-auto object-cover">
                                            </label>
                                        </div>
                                        <div class="form-check sidebar-setting card-radio">
                                            <input class="form-check-input" type="radio" name="data-sidebar-image" id="sidebarimg-03" value="img-3">
                                            <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-03">
                                                <img src="{{URL::asset('assets/images/sidebar/img-3.jpg')}}" class="avatar-md w-auto object-cover">
                                            </label>
                                        </div>
                                        <div class="form-check sidebar-setting card-radio">
                                            <input class="form-check-input" type="radio" name="data-sidebar-image" id="sidebarimg-04" value="img-4">
                                            <label class="form-check-label p-0 avatar-sm h-auto" for="sidebarimg-04">
                                                <img src="{{URL::asset('assets/images/sidebar/img-4.jpg')}}" class="avatar-md w-auto object-cover">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="offcanvas-footer border-top p-3 text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary w-100" id="reset-layout">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>

@endsection

@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
@endsection
