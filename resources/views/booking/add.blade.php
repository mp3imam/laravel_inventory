@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"></h4>
            </div><!-- end card header -->

            <div class="card-body">
                <form action="{{route('save_antrian')}}" method="post" enctype="multipart/form-data">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-gen-info" role="tabpanel" aria-labelledby="pills-gen-info-tab">
                            <div>
                                <div class="row">
                                    <div class="d-grid gap-2 text-center mb-2">
                                        @error('Duplicate')
                                            <span class="text-danger">{{ $errors->first('Duplicate') }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="provinsi_id">Pilih Provinsi : <span class="text-danger">*</span></label>
                                            <select name="provinsi_id" id="provinsi_id" class="form-select form-control"></select>
                                            @error('provinsi_id')
                                                <div class="text-danger">Pilih Data Provinsi</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="satker_id">Pilih Satker : <span class="text-danger">*</span></label>
                                            <select id="satker_id" name="satker_id" class="form-select form-control" disabled></select>
                                            @error('satker_id')
                                                <div class="text-danger">Pilih Data Satker</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="user">Nama User : </label>
                                            <input class="form-control" name="user" value="{{ $satker->name }}" readonly>
                                            <input class="form-control" name="user_id" value="{{ $satker->id }}" hidden>
                                            <input class="form-control" name="provinsi" id="provinsi" hidden/>
                                            <input class="form-control" name="satker" id="satker" hidden/>
                                            <input class="form-control" name="layanan" id="layanan" hidden/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" hidden>
                                        <div class="mb-3">
                                            <label class="form-label" for="no_hp">Nomor Handphone :</label>
                                            <input class="form-control" name="no_hp"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="layanan_id">Pilih Layanan : <span class="text-danger">*</span></label>
                                            <select id="layanan_id" name="layanan_id" class="form-select form-control" disabled></select>
                                            @error('layanan_id')
                                                <div class="text-danger">Pilih Data Layanan</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="layanan">Pilih Tanggal Hadir : <span class="text-danger">*</span></label>
                                            <div class="input-group date" data-provide="datepicker">
                                                <input type="text" name="tanggal_hadir" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d-m-Y" data-mindate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" data-maxdate="{{ \Carbon\Carbon::now()->addDays(1)->format('d-m-Y') }}" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                                                @error('tanggal_hadir')
                                                    <div class="text-danger">Pilih Data Tanggal<div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="keterangan">Keterangan :</label>
                                            <input class="form-control" name="keterangan"/>
                                        </div>
                                    </div>
                                <div class="col-lg-6 text-center align-center camera" hidden>
                                    <div id="my_camera"></div>
                                        <input class="btn btn-success mt-2" type=button value="Take Snapshot" onClick="take_snapshot()">
                                        <input type="hidden" name="image" class="image-tag">
                                </div>
                                <div class="col-lg-6 text-center">
                                        <div id="results"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 text-center mt-2">
                                <button type="submit" class="btn btn-primary btn-block save_antrian">Simpan</button>
                            </div>
                        </div>
                        <!-- end tab pane -->
                    </div>
                    <!-- end tab content -->
                </form>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
@section('script')
<script src="{{ URL::asset('assets/js/pages/form-wizard.init.js') }}"></script>
<script>
    $('#provinsi_id').select2({
        placeholder: "Pilih Provinsi",
        ajax: {
          url: "{{ route('api.provinsi') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
                results: $.map(data.data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
    });

    $('#satker_id').select2();

    $('#provinsi_id').change(function() {
        $("#satker_id").prop("disabled", false)
        $('#satker_id').val('')
        $('#layanan_id').val('').trigger('change')
        $("#layanan_id").prop("disabled", true)
        var provinsi_id = $(this).val()
        $("#provinsi").val($("#provinsi_id option:selected").text());
        $('#satker_id').select2({
            placeholder: "Pilih Satker",
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ url('api/satker') }}" + '?provinsi_id=' + provinsi_id,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                return {
                    results: $.map(data.data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
                }
            }
        });
    });

    $('#satker_id').change(function() {
        $("#layanan_id").prop("disabled", false)
        $('#layanan_id').val('')
        var satker_id = $(this).val()
        $("#satker").val($("#satker_id option:selected").text());
        $('#layanan_id').select2({
            placeholder: "Pilih Layanan",
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ url('api/layanan_sakters') }}" + '?satker_id=' + satker_id,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                return {
                    results: $.map(data.data, function(item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
                }
            }
        });
    });

    $('#satker_id').change(function() {
        $("#satker").val($("#satker_id option:selected").text());
    });

    $('#layanan_id').change(function() {
        $("#layanan").val($("#layanan_id option:selected").text());
    });

    if (window.location.protocol == 'https:'){
        Webcam.set({
            width: 490,
            height: 350,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach( '#my_camera' );

        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
            } );
        }

        $('.camera').attr('hidden', false)
    }

</script>
@endsection
