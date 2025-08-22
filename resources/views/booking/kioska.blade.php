@extends('layouts.master-layouts')
@section('title')
    @lang('translation.signin')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-6">
            <div data-simplebar data-simplebar-auto-hide="true" data-simplebar-track="warning"
                style="height:650px; overflow:scroll;">
                <div class="card-block" style="background-color:#17594A">
                    <div id="card_layanan" class="row row-cols-lg-2 row-cols-1 m-3">
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 btn_otp mt-4" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center-otp">
                <div class="card card-success mt-2">
                    <div class="text-center m-3">
                        <a href="#" class="link-light fs-20">Masukan OTP</a>
                    </div>
                </div>
            </div>

            <input id="provinsi_id" hidden value="{{ $satker['provinsis']['id'] }}" />
            <input id="provinsi" hidden value="{{ $satker['provinsis']['name'] }}" />
            <input id="satker_id" hidden value="{{ $satker['id'] }}" />
            <input id="satker" hidden value="{{ $satker['name'] }}" />
            <input id="kiosk" hidden value="{{ $satker['kiosk'] }}" />
            <input id="kode_satker" hidden value="{{ $satker['kode_satker'] }}" />
        </div><!-- end col -->

        <div class="col-xl-6" style="display: grid">
            <div class="card-block">
                <div id="carouselExampleControlsNoTouching" class="carousel slide bg-black" data-bs-touch="false"
                    data-bs-interval="false">
                    <div class="carousel-inner video_player"></div>
                </div>
            </div>
            <div class="card-block">
                <div class="card-body">
                    <div id="carouselExampleInterval" class="carousel slide row g-0 bg-light position-relative"
                        data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($banners as $b)
                                @php $active = $loop->iteration == 1 ? 'active' : ''; @endphp
                                <div class="carousel-item {{ $active }}">
                                    <img class="d-block mx-auto" src="{{ $b['banner'] }}" alt="..." width="700"
                                        height="120" style="background-size: 100%;">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end row -->
    <div class="modal fade bs-example-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-modal="true"
        role="dialog" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center" id="modal_content_layanan">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-example-modal-center-otp" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-modal="true"
        role="dialog" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center" id="modal_content_otp">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
    <script type="text/javascript">
        var provinsi_id = $('#provinsi_id').val()
        var provinsi = $('#provinsi').val()
        var satker_id = $('#satker_id').val()
        var kode_satker = $('#kode_satker').val()
        var satker = $('#satker').val()
        var kiosk = $('#kiosk').val()
        $(function() {
            $('.auth-page-wrapper').scrolling = 'no';
            $.ajax({
                type: "get",
                url: "{{ url('video.play') }}/" + satker_id,
                success: function(data) {
                    if (data['video'].length > 0) {
                        data.video.forEach(function callback(e, index) {
                            autoplay = (index == 0) ? "autoplay" : ""
                            active = (index == 0) ? "active" : ""
                            $('.video_player').append(`
                                <center>
                                    <div class="carousel-item ` + active + `">
                                        <video id="video_play` + index +
                                `" width="100%" height="650px" muted controls autoplay="` +
                                autoplay + `" style="object-fit: fill;">
                                            <source src="` + e['video'] + `" type="video/mp4">
                                        </video>
                                    </div>
                                </center>
                            `)
                        })
                        $('.carousel-inner').append(`
                    <button hidden class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button hidden class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    `)
                    } else {
                        $('.carousel-inner').append(`
                        <center class="p-4" style="font-color:yellow; font-size:16px; background-color:white">
                            <label> Data Video belum diaktifkan</label>
                        </center>
                    `)
                    }
                }
            })
        })

        function daftar_layanan() {
            $.ajax({
                type: "get",
                url: "{{ url('data_antrian') }}/" + satker_id,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result['data'].length > 0) {
                        result.data.forEach(function callback(e, index) {
                            if (localStorage.getItem(
                                    e['kode_layanan'] + tgl) != e['antrian_saat_ini'] && e[
                                    'antrian_saat_ini'].substr(e['antrian_saat_ini'].length - 3) !==
                                "000") {
                                $(".remove_antrian" + index).remove()
                                $('.data_antrian' + index).append(`
                                <label class="text-white remove_antrian` + index + `" style="font-size:60px">` + e[
                                    'antrian_saat_ini'] + `</label>
                            `)
                                localStorage.setItem(e['kode_layanan'] + tgl, e['antrian_saat_ini'])
                                console.log(localStorage.getItem(e['kode_layanan'] + tgl))
                            }

                            $(".remove_jumlah" + index).remove()
                            $('.data_jumlah' + index).append(`
                                <label class="fs-16 remove_jumlah` + index +
                                `" style="color:#FFFFF0">Jumlah Antrian: ` + e['total_antrian'] + `</label>
                            `)

                        })
                    } else {
                        $('#card_layanan').append(`
                        <div class="remove mx-auto">
                            <div class="card card-body ">
                                <label>Data Layanan belum diaktifkan</label>
                            </div>
                        </div>
                    `)
                    }
                }
            });
        }

        startup()
        var dt = new Date()
        var tgl = dt.getFullYear() + '-' + dt.getMonth() + '-' + dt.getDate()

        setInterval(() => {
            var dt = new Date()
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds()
            if (dt.getMinutes() + ":" + dt.getSeconds() == "0:0" || dt.getMinutes() + ":" + dt.getSeconds() ==
                "30:0") location.reload()
        }, 1000);

        function startup() {
            $.ajax({
                type: "get",
                url: "{{ url('data_antrian') }}/" + satker_id,
                processData: false,
                contentType: false,
                success: function(result) {
                    $(".remove").remove()
                    if (result['data'].length > 0) {
                        result.data.forEach(function callback(e, index) {
                            var img = e['icon'] !== null ? `<img src="` + e['icon'] +
                                `" alt="" class="avatar-sm rounded-circle" style="height:50%">` :
                                `<img src="{{ asset('assets/images/logo/kejaksaan-logo.png') }}" class="avatar-sm rounded-circle" style="height:50%">`
                            var result = localStorage.getItem(e['kode_layanan'] + tgl) ?? e[
                                'antrian_saat_ini']
                            $('#card_layanan').append(`
                        <div class="remove col-xxl-6" onclick="buat_antrian(` + e['id'] + `,'` + e[
                                    'nama_layanan'] + `')">
                            <div class="card" style="background-color:` + e['color'] + `">
                                <div class="text-uppercase text-center m-2 mb-2">
                                    <h5 style="color:#FFFFF0">` + e['nama_layanan'] + `</h5>
                                </div>
                                <div class="text-uppercase text-center">
                                    <h5 class="mb-1" style="color:#FFFFF0">Nomor Antrian</h5>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 text-center data_antrian` + index + `">
                                        <label class="text-white remove_antrian` + index +
                                `" style="font-size:60px">` + result + `</label>
                                    </div>
                                </div>
                                <div class="text-uppercase text-center data_jumlah` + index + `">
                                    <label class="fs-16 remove_jumlah` + index +
                                `" style="color:#FFFFF0">Jumlah Antrian: ` + e['total_antrian'] + `</label>
                                </div>
                                <div class="m-2 text-uppercase text-center fs-16 m-3 mb-4" style="background-color:#25D366">
                                    <label class="m-2" style="color:#FFFFF0">Daftar</label>
                                </div>
                            </div>
                        </div>
                        `)
                            if (localStorage.getItem(e['kode_layanan'] + tgl) === null) localStorage
                                .setItem(e['kode_layanan'] + tgl, e['antrian_saat_ini'])
                        })
                    } else {
                        $('#card_layanan').append(`
                        <div class="remove mx-auto">
                            <div class="card card-body ">
                                <label>Data Layanan belum diaktifkan</label>
                            </div>
                        </div>
                    `)
                    }
                }
            });
        }

        setInterval(() => {
            $('#video_play0')[0].play()
            $('#video_play0').on('ended', function() {
                $('.carousel-control-next-icon').click();
                $('#video_play1')[0] ? $('#video_play1')[0].play() : $('#video_play0')[0].play()
            });
            $('#video_play1').on('ended', function() {
                $('.carousel-control-next-icon').click();
                $('#video_play2')[0] ? $('#video_play2')[0].play() : $('#video_play0')[0].play()
            });
            $('#video_play2').on('ended', function() {
                $('.carousel-control-next-icon').click();
                $('#video_play3')[0] ? $('#video_play3')[0].play() : $('#video_play0')[0].play()
            });
            $('#video_play3').on('ended', function() {
                $('.carousel-control-next-icon').click();
                $('#video_play4')[0] ? $('#video_play4')[0].play() : $('#video_play0')[0].play()
            });
            $('#video_play4').on('ended', function() {
                $('.carousel-control-next-icon').click();
                $('#video_play5')[0] ? $('#video_play5')[0].play() : $('#video_play0')[0].play()
            });
            $('#video_play5').on('ended', function() {
                $('.carousel-control-next-icon').click();
                $('#video_play0')[0].play()
            });
        }, 1000);

        function buat_antrian(id, layanan) {
            Swal.fire({
                title: layanan,
                text: "Apakah anda yakin ingin menambahkan antrian?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya',
                cancelButtonText: 'Batalkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    var fd = new FormData()
                    fd.append('provinsi_id', provinsi_id)
                    fd.append('provinsi', provinsi)
                    fd.append('satker_id', satker_id)
                    fd.append('satker', satker)
                    fd.append('layanan_id', id)
                    fd.append('layanan', layanan)
                    fd.append('tanggal_hadir', "{{ date('Y-m-d') }}")
                    $.ajax({
                        type: 'post',
                        url: "{{ route('save_guest_kiosk') }}",
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            cetak_antrian(result.data['layanan'], result.data['nomor_antrian'])
                            daftar_layanan()
                            Swal.fire(
                                result.data['layanan'],
                                'Anda antrian nomor ' + result.data['nomor_antrian'],
                                'success'
                            )
                        }
                    });
                }
            })
        }

        $(document).ready(function() {
            setInterval(function() {
                daftar_layanan()
            }, 4000);
        });

        function simpan_kiosk() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            var fd = new FormData()
            fd.append('satker_id', satker_id)
            fd.append('kiosk', $('#key').val())

            $.ajax({
                type: 'post',
                url: "{{ route('save_appkey_kiosk') }}",
                data: fd,
                processData: false,
                contentType: false,
                success: function(result) {
                    Swal.fire(
                        'Berhasil Input',
                        'nomor appkey ' + result.message['kiosk'] + ' ke tempat ' + result.message['name'],
                        'success'
                    )
                }
            });
        }

        var line = "======================================================="

        function cetak_antrian(layanan, nomor_antrian) {
            $('#modal_content_layanan').html(`
                <div id="ticket">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td class="text-center">
                            <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt="" height="100"><br>
                                <h6>` + line + `</h6>
                                <h4>Kejaksaan Republik Indonesia</h4>
                                <h4>` + satker + `</h4>
                                <h6>` + line + `</h6>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <h4>Nomor Antrian</h4>
                                    <label style="font-size:70px">` + nomor_antrian + `</label><br>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>Layanan : ` + layanan + `</h4>
                                    <h4>Tanggal : {{ date('d-m-Y') }}</h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <h6>` + line + `</h6>
                                    <h4>{{ $printout_bottom_kiosk }}</h4>
                                    <h6>` + line + `</h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `)

            var printContents = document.getElementById('ticket').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        $('.btn_otp').on('click', function() {
            $('#modal_content_otp').html(`
        <div class="row" style="bottom:-200px">
            <div class="col-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="avatar-lg mx-auto">
                                <div class="avatar-title bg-light text-primary display-5 rounded-circle">
                                    <i class="ri-shield-user-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 mt-4">
                            <div class="text-muted text-center mb-4 mx-lg-3">
                                <h4>Masukan Nomor OTP</h4>
                                <p>Masukan 6 Digit</p>
                            </div>
                                <div class="row">
                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="digit1-input" class="visually-hidden">Digit 1</label>
                                            <input id="digit1" class="form-control form-control-lg bg-light border-black text-center" style="font-size:14px" onkeyup="moveToNext(2)" maxlength="1">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="digit2-input" class="visually-hidden">Digit 2</label>
                                            <input id="digit2" class="form-control form-control-lg bg-light border-black text-center" style="font-size:14px" onkeyup="moveToNext(3)" maxlength="1">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="digit3-input" class="visually-hidden">Digit 3</label>
                                            <input id="digit3" class="form-control form-control-lg bg-light border-black text-center" style="font-size:14px" onkeyup="moveToNext(4)" maxlength="1">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="digit4-input" class="visually-hidden">Digit 4</label>
                                            <input id="digit4" class="form-control form-control-lg bg-light border-black text-center" style="font-size:14px" onkeyup="moveToNext(5)" maxlength="1">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="digit5-input" class="visually-hidden">Digit 5</label>
                                            <input id="digit5" class="form-control form-control-lg bg-light border-black text-center" style="font-size:14px" onkeyup="moveToNext(6)" maxlength="1">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="mb-3">
                                            <label for="digit6-input" class="visually-hidden">Digit 6</label>
                                            <input id="digit6" class="form-control form-control-lg bg-light border-black text-center" onkeyup="moveToNext('simpan')" style="font-size:14px" maxlength="1">
                                        </div>
                                    </div>
                                </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-success w-100 btn_konfirmasi_otp">Konfirmasi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `)

            $('#exampleModal').on('shown.bs.modal', function() {
                $('#digit1').focus();
            })
            // $('#exampleModal').modal('show')

            // copy paste
            $("#digit1").bind("paste", function(e) {
                var data = e.originalEvent.clipboardData.getData('text').split('')
                $('#digit1').val(data[0])
                $('#digit2').val(data[1])
                $('#digit3').val(data[2])
                $('#digit4').val(data[3])
                $('#digit5').val(data[4])
                $('#digit6').val(data[5])
                $('.btn_konfirmasi_otp').click()
            });

            $('.btn_konfirmasi_otp').on('click', function() {
                $('#exampleModal').modal('hide')
                var fd = new FormData()
                fd.append('satker_id', $('#satker_id').val())
                fd.append('digit1', $('#digit1').val())
                fd.append('digit2', $('#digit2').val())
                fd.append('digit3', $('#digit3').val())
                fd.append('digit4', $('#digit4').val())
                fd.append('digit5', $('#digit5').val())
                fd.append('digit6', $('#digit6').val())
                $.ajax({
                    type: 'post',
                    url: "{{ route('cek_guest_kiosk') }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result['status'] == 200) {
                            cetak_antrian(result.data['layanan'], result.nomor_antrian)
                            Swal.fire(
                                result.data['layanan'],
                                'Anda antrian nomor ' + result.data['nomor_antrian'],
                                'success'
                            )
                        } else if (result['status'] == 226) {
                            Swal.fire(
                                result.data['layanan'],
                                'Nomor OTP sudah digunakan ' + result.data['otp'],
                                'success'
                            )
                        } else if (result['status'] == 100) {
                            daftar_layanan()

                            cetak_antrian(result.data['layanan'], result.nomor_antrian)
                            Swal.fire(
                                result.data['layanan'],
                                'Nomor anda sudah terlewat, ini nomor antrian anda yang baru ' +
                                result.data['nomor_antrian'],
                                'warning'
                            )
                        } else {
                            Swal.fire(
                                'Info!',
                                'Mohon cek kembali nomor otp anda',
                                'warning'
                            )
                        }
                    }
                });
            })

            // header ajax
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        })

        // waktu
        setInterval(() => {
            var dt = new Date()
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds()
            if (dt.getMinutes() + ":" + dt.getSeconds() == "0:0" || dt.getMinutes() + ":" + dt.getSeconds() ==
                "30:0") location.reload()
            $("#waktu").text(time)
        }, 1000);

        function moveToNext(val) {
            if (val == 'simpan') {
                if (!isNaN($('#digit6').val())) {
                    $('.btn_konfirmasi_otp').click()
                } else {
                    $('#digit6').val('')
                }
            } else {
                if (!isNaN($('#digit' + (val - 1)).val())) {
                    if ($('#digit' + val).val('') == null) $('#digit' + val).val('')
                    $('#digit' + val).focus()
                } else {
                    $('#digit' + (val - 1)).val('')
                }
            }
        }
    </script>
@endsection
