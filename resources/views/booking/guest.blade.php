@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.signin')
@endsection
@section('content')

<div class="auth-page-wrapper py-2">
    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <section class="section bg-light" id="creators">
                @if(session()->has('dataError'))
                    <div class="alert alert-error">
                        <div class="alert alert-danger alert-dismissible alert-outline fade show mb-xl-0" role="alert">
                            Data Tidak Tersimpan. Mohon yang bertanda bintang merah (*) untuk wajib di isi
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="container">
                    <div class="row">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="text-center mb-3">
                                    <img src="{{ asset('assets/images/logo/kejaksaan-logo.png') }}" alt="" height="150">
                                    <h1 class="mb-3 fw-semibold lh-base">Booking Layanan</h1>
                                    <p>{{ date('D, d M Y') }}</p>
                                </div>
                            </div>
                        </div><!-- end row -->
                        <form action="{{route('save_guest')}}" method="post" enctype="multipart/form-data">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="pills-gen-info" role="tabpanel" aria-labelledby="pills-gen-info-tab">
                                    <div>
                                        <div class="row">
                                            <div class="col-lg-12 mb-3">
                                                <label class="form-label" for="nama">Nama : <span class="text-danger">*(wajib diisi)</span></label>
                                                <input name="nama" id="nama" class="form-select form-control" />
                                                @error('nama')
                                                    <div class="text-danger">Masukan Data Nama</div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12 mb-3">
                                                <label class="form-label" for="email">Email : <span class="text-danger">*(wajib diisi)</span></label>
                                                <input name="email" id="email" class="form-select form-control" />
                                                @error('email')
                                                    <div class="text-danger">Mohon di isi data Email Bertujuan memberikan rating yang akan kami kirimkan via email. Terima kasih</div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="email">Provinsi : <span class="text-danger">*(wajib diisi)</span></label>
                                                    <select name="provinsi_id" id="provinsi_id" class="form-select form-control"></select>
                                                    <input class="form-control" name="provinsi" id="provinsi" hidden/>
                                                    <input class="form-control" name="satker" id="satker" hidden/>
                                                    <input class="form-control" name="layanan" id="layanan" hidden/>
                                                            @error('provinsi_id')
                                                        <div class="text-danger">Pilih Data Provinsi</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="satker_id">Pilih Satker : <span class="text-danger">*(wajib diisi)</span></label>
                                                    <select id="satker_id" name="satker_id" class="form-select form-control" disabled></select>
                                                    @error('satker_id')
                                                        <div class="text-danger">Pilih Data Satker</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="layanan_id">Pilih Layanan : <span class="text-danger">*(wajib diisi)</span></label>
                                                    <select id="layanan_id" name="layanan_id" class="form-select form-control" disabled></select>
                                                    <span class="text-primary">Info: Jika Layanan kosong, Silahkan hubungi satker yang bersangkutan</span>
                                                    @error('layanan_id')
                                                        <div class="text-danger">Pilih Data Layanan</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="layanan">Pilih Tanggal Hadir : <span class="text-danger">*(wajib diisi)</span></label>
                                                    <div class="input-group date" data-provide="datepicker">
                                                        <input type="date" name="tanggal_hadir" class="form-control" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->addDays(1)->format('Y-m-d') }}" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                                        @error('tanggal_hadir')
                                                            <div class="text-danger">Pilih Data Tanggal<div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="keterangan">Keterangan :</label>
                                                    <input class="form-control" name="keterangan"/>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="d-grid gap-2 text-center mt-2">
                                        <button type="submit" class="btn btn-primary btn-block save_antrian">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>@endsection
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

</script>
@endsection
