@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.success-message')
@endsection
<!-- default styles -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/css/star-rating.min.css" media="all"
    rel="stylesheet" type="text/css" />

<!-- with v4.1.0 Krajee SVG theme is used as default (and must be loaded as below) - include any of the other theme CSS files as mentioned below (and change the theme property of the plugin) -->
<link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/themes/krajee-svg/theme.css" media="all"
    rel="stylesheet" type="text/css" />

<!-- important mandatory libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/js/star-rating.min.js"
    type="text/javascript"></script>

<!-- with v4.1.0 Krajee SVG theme is used as default (and must be loaded as below) - include any of the other theme JS files as mentioned below (and change the theme property of the plugin) -->
<script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/themes/krajee-svg/theme.js"></script>

@section('content')
    <div class="auth-page-wrapper pt-4">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>
        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-8">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-center align-self-center">
                                    <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt=""
                                        height="150" class="mb-2"><br>
                                    <label class="text-center h2">{{ $antrian->satker }}</label><br>
                                    <label class="text-center h4">{{ $antrian->layanan }}</label>
                                </div>
                                <input class="text-center" id="provinsi_id" name="provinsi_id"
                                    value="{{ $antrian->provinsi_id }}" hidden />
                                <input class="text-center" id="satker_id" name="satker_id" value="{{ $antrian->satker_id }}"
                                    hidden />
                                <input class="text-center" id="layanan_id" name="layanan_id"
                                    value="{{ $antrian->layanan_id }}" hidden />
                                <input class="text-center" id="user_id" name="user_id" value="{{ $antrian->user_id }}"
                                    hidden />
                                <input class="text-center" id="question_id" name="question_id"
                                    value="{{ $antrian->question_id }}" hidden />
                                <input class="text-center" id="id" name="id" value="{{ $antrian->id }}"
                                    hidden />

                            </div>
                            @foreach ($questions as $q)
                                <div class="m-3">
                                    <label for="input-{{ $loop->iteration }}"
                                        class="control-label">{{ $loop->iteration . '. ' . $q['pertanyaan'] }}</label>
                                    <input id="input-{{ $loop->iteration }}" name="input-{{ $loop->iteration }}"
                                        class="rating" data-min="0" value="4" data-max="6" data-show-clear="false"
                                        data-show-caption="false" data-stars="6" data-step="1" data-size="xl">
                                </div>
                            @endforeach
                            <div class="mt-4">
                                <button class="btn btn-success w-100">Submit</button>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection
@section('script')
    <script type="text/javascript">
        $('.btn-success').on('click', function() {
            var fd = new FormData()
            fd.append('provinsi_id', $('#provinsi_id').val())
            fd.append('satker_id', $('#satker_id').val())
            fd.append('layanan_id', $('#layanan_id').val())
            fd.append('user_id', $('#user_id').val())
            fd.append('id', $('#id').val())
            fd.append('answer_1', $('#input-1').val())
            fd.append('answer_2', $('#input-2').val())
            fd.append('answer_3', $('#input-3').val())
            fd.append('answer_4', $('#input-4').val())
            fd.append('answer_5', $('#input-5').val())
            fd.append('answer_6', $('#input-6').val())
            fd.append('answer_7', $('#input-7').val())
            fd.append('answer_8', $('#input-8').val())
            fd.append('answer_9', $('#input-9').val())
            fd.append('answer_10', $('#input-10').val())
            fd.append('answer_11', $('#input-11').val())
            fd.append('answer_12', $('#input-12').val())
            fd.append('answer_13', $('#input-13').val())
            fd.append('answer_14', $('#input-14').val())
            $.ajax({
                type: 'post',
                url: "{{ route('rating_answer') }}",
                data: fd,
                processData: false,
                contentType: false,
                success: function(blob, status, xhr) {
                    window.location.href = "{{ route('user_rating_success') }}"
                }
            })
        })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
@endsection
