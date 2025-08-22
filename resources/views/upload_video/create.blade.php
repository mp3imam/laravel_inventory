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
                    <form id="fileUploadForm" action="{{ route('upload_video.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 mb-4">
                            <label for="video" class="form-label">Pilih Kategori</label>
                            <select id='category_id' name="category_id" class="form-control" id="choices-single-no-search"
                                name="choices-single-no-search" data-choices data-choices-search-false
                                data-choices-removeItem>
                                <option value='1' selected>{{ \App\Models\UploadVideoModel::KATEGORY_SMARTTV }}
                                </option>
                                <option value='2'>{{ \App\Models\UploadVideoModel::KATEGORY_KIOSK }}
                            </select>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="video" class="form-label">Upload Video</label>
                            <input type="file" id="video" name="video" class="form-control demoInputBox"
                                accept="video/mp4,video/x-m4v,video/*" />
                            <div><span id="file_error" class="text-danger"></span></div>
                            {{-- <input type="file" id="video" name="video" class="form-control" /> --}}
                            <label class="text-mute">Video file size Max 100 Mb</label>
                            @error('video')
                                <div class="alert alert-danger alert-dismissible fade show mb-xl-0" role="alert">
                                    <span>Video tidak boleh kosong dan ukuran tidak boleh melebihi 100 MB</span>
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
@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"
        integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        $('#fileUploadForm').ajaxForm({
            beforeSend: function() {
                $('.btn-success').attr('disabled', false)
                $("#file_error").html("");
                $(".demoInputBox").css("border-color", "#F0F0F0");
                var file_size = $('#video')[0].files[0].size;
                if (file_size > 20971520 * 5) {
                    $("#file_error").html("Video melebihi ukuran 100MB");
                    $(".demoInputBox").css("border-color", "#FF0000");
                    $('.progress .progress-bar').css("width", 0)
                    $('.btn-success').attr('disabled', false)
                    return false;
                }
                var percentage = '0'
            },
            uploadProgress: function(event, position, total, percentComplete) {
                $('.btn-success').attr('disabled', true)
                $("#file_error").html("");
                $(".demoInputBox").css("border-color", "#F0F0F0");
                var file_size = $('#video')[0].files[0].size;
                if (file_size > 20971520 * 5) {
                    $("#file_error").html("Video melebihi ukuran 100MB");
                    $(".demoInputBox").css("border-color", "#FF0000");
                    $('.progress .progress-bar').css("width", 0)
                    $('.btn-success').attr('disabled', false)
                    return false;
                }
                var percentage = percentComplete
                $('.progress .progress-bar').css("width", percentage + '%', function() {
                    return $(this).attr("aria-valuenow", percentage) + "%"
                })
            },
            complete: function(xhr) {
                $('.btn-success').attr('disabled', false)
                $("#file_error").html("");
                $(".demoInputBox").css("border-color", "#F0F0F0");
                var file_size = $('#video')[0].files[0].size;
                if (file_size > 20971520 * 5) {
                    $("#file_error").html("Video melebihi ukuran 100MB");
                    $(".demoInputBox").css("border-color", "#FF0000");
                    $('.progress .progress-bar').css("width", 0)
                    return false;
                } else {
                    window.location.href = "{{ route('upload_video.index') }}"
                }
            }
        })
    </script>
@endsection
