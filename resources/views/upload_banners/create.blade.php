@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-4">
                    <form id="fileUploadForm" action="{{ route('upload_banner.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 mb-4">
                            <label for="video" class="form-label">Pilih Kategori</label>
                            <select id='category_id' name="category_id" class="form-control" id="choices-single-no-search"
                                name="choices-single-no-search" data-choices data-choices-search-false
                                data-choices-removeItem>
                                <option value='1' selected>{{ \App\Models\UploadBannerModel::KATEGORY_SMARTTV }}
                                </option>
                                <option value='2'>{{ \App\Models\UploadBannerModel::KATEGORY_KIOSK }}
                            </select>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="video" class="form-label">Upload Banner</label>
                            <input type="file" id="banner" name="banner" class="form-control demoInputBox"
                                accept="image/*" />
                            <span class="text-danger fs-10">Ukuran yang disarankan 720 pixel X 120 pixel</span>
                            <div><span id="file_error" class="text-danger"></span></div>

                            @error('banner')
                                <div class="alert alert-danger alert-dismissible fade show mb-xl-0" role="alert">
                                    <span>Video tidak boleh kosong</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success form-control"><i
                                class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan</button>
                    </form>
                    <div class="form-group">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                style="width: 0%"></div>
                        </div>
                    </div>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
    <!--end row-->
@endsection
