@extends('layouts.master-without-nav-sistem-antrian')
@section('title')
    @lang('translation.signin')
@endsection
@section('css')
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
@endsection
@section('content')
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
        aria-modal="true" style="display: none; padding-left: 0px;" display="none">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                        colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px"></lord-icon>

                    <div class="mt-4">
                        <h4 class="mb-3">Generate PDF</h4>
                        <p class="text-muted mb-4"> Selamat, Data Antrian dengan nomor antrian
                            {{ session()->get('nomor_antrian') }} akan ter-Download automatis dan silahkan dicetak
                            (print)
                            untuk mengajukan Antrian ke Kejaksaan. Mohon Tunggu Sebentar... </p>
                    </div>
                </div>
            </div>
        </div>
        <input id="antrian" hidden value="{{ session()->get('id') }}" />
    </div>
    <div class="vh-100" style="background-color: #116D6E">
        <div class="header" style="background-color: #116D6E">
            <div class="row">
                <div class="col-xxl-1 p-2 text-center">
                    <img src="{{ asset('assets/images/logo/kejaksaan-logo.png') }}" width="100px" alt="">

                </div>
                <div class="col-xxl-9 mt-5 justify-content-end">

                    <input id="satker_id" hidden value="{{ $satker['id'] }}" />
                    <h3 class="text-white" style="font-size: 28px;">{{ $satker->name }}</h3>
                    <h6 class="text-white" style="font-size: 16px; padding-left:2px;">{{ $satker->address }}</h6>
                </div>
                <div class="col-xxl-2 text-end p-3">
                    @php
                        switch (date('D')) {
                            case 'Mon':
                                $hari = 'Senin';
                                break;
                            case 'Tue':
                                $hari = 'Selasa';
                                break;
                            case 'Wed':
                                $hari = 'Rabu';
                                break;
                            case 'Thu':
                                $hari = 'Kamis';
                                break;
                            case 'Fri':
                                $hari = 'Jumat';
                                break;
                            case 'Sat':
                                $hari = 'Sabtu';
                                break;

                            default:
                                $hari = 'Minggu';
                                break;
                        }
                    @endphp
                    <div id="waktu" class="text-white mt-5 text-right h1" style="font-size: 30px;">{{ date('H:i:s') }}
                    </div>
                    <h1 class="tanggal text-white text-right">
                        {{ $hari . ', ' . date('d/m/Y') }}
                        <h2 id="demo"></h2>
                    </h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-8 p-1">
                <div class="card-block text-center">
                    <div id="carouselExampleControlsNoTouching" class="carousel slide bg-black" data-bs-touch="false"
                        data-bs-interval="false">
                        <div class="carousel-inner video_player"></div>
                    </div>
                </div>

            </div>
            <div class="col-xxl-4" style="background-color: #176B87;">
                <div id="card_layanan">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-8">
                <!-- With Controls -->
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div id="card_banner" class="carousel-inner" role="listbox">
                    </div>
                    <a hidden class="carousel-control-prev" href="#carouselExampleControls" role="button"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a hidden class="carousel-control-next" href="#carouselExampleControls" role="button"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-xxl-4" style="background-color: #176B87;">
                <img src="{{ asset('assets/images/logo/moto.jpeg') }}" width="630px" height="250px" alt="moto">
            </div>
        </div>
    </div>
    <div>
        <audio id="opening" hidden>
            <source src="{{ URL::asset('storage/suara_mp3/opening.mp3') }}" type="audio/mpeg">
        </audio>

        <audio id="nomor_antrian" src="{{ URL::asset('storage/suara_mp3/nomor_antrian.mp3') }}" hidden></audio>
        <audio id="ke_loket" src="{{ URL::asset('storage/suara_mp3/ke_loket.mp3') }}" hidden></audio>
        <audio id="ending" src="{{ URL::asset('storage/suara_mp3/ending.mp3') }}" hidden></audio>
        <audio id="0" src="{{ URL::asset('storage/suara_mp3/0.mp3') }}" hidden></audio>
        <audio id="1" src="{{ URL::asset('storage/suara_mp3/1.mp3') }}" hidden></audio>
        <audio id="2" src="{{ URL::asset('storage/suara_mp3/2.mp3') }}" hidden></audio>
        <audio id="3" src="{{ URL::asset('storage/suara_mp3/3.mp3') }}" hidden></audio>
        <audio id="4" src="{{ URL::asset('storage/suara_mp3/4.mp3') }}" hidden></audio>
        <audio id="5" src="{{ URL::asset('storage/suara_mp3/5.mp3') }}" hidden></audio>
        <audio id="6" src="{{ URL::asset('storage/suara_mp3/6.mp3') }}" hidden></audio>
        <audio id="7" src="{{ URL::asset('storage/suara_mp3/7.mp3') }}" hidden></audio>
        <audio id="8" src="{{ URL::asset('storage/suara_mp3/8.mp3') }}" hidden></audio>
        <audio id="9" src="{{ URL::asset('storage/suara_mp3/9.mp3') }}" hidden></audio>
        <audio id="a" src="{{ URL::asset('storage/suara_mp3/A.mp3') }}" hidden></audio>
        <audio id="b" src="{{ URL::asset('storage/suara_mp3/B.mp3') }}" hidden></audio>
        <audio id="c" src="{{ URL::asset('storage/suara_mp3/C.mp3') }}" hidden></audio>
        <audio id="d" src="{{ URL::asset('storage/suara_mp3/D.mp3') }}" hidden></audio>
        <audio id="e" src="{{ URL::asset('storage/suara_mp3/E.mp3') }}" hidden></audio>
        <audio id="f" src="{{ URL::asset('storage/suara_mp3/F.mp3') }}" hidden></audio>
        <audio id="g" src="{{ URL::asset('storage/suara_mp3/G.mp3') }}" hidden></audio>
        <audio id="h" src="{{ URL::asset('storage/suara_mp3/H.mp3') }}" hidden></audio>
        <audio id="i" src="{{ URL::asset('storage/suara_mp3/I.mp3') }}" hidden></audio>
        <audio id="j" src="{{ URL::asset('storage/suara_mp3/J.mp3') }}" hidden></audio>
        <audio id="k" src="{{ URL::asset('storage/suara_mp3/K.mp3') }}" hidden></audio>
        <audio id="l" src="{{ URL::asset('storage/suara_mp3/L.mp3') }}" hidden></audio>
        <audio id="m" src="{{ URL::asset('storage/suara_mp3/M.mp3') }}" hidden></audio>
        <audio id="n" src="{{ URL::asset('storage/suara_mp3/N.mp3') }}" hidden></audio>
        <audio id="o" src="{{ URL::asset('storage/suara_mp3/O.mp3') }}" hidden></audio>
        <audio id="p" src="{{ URL::asset('storage/suara_mp3/P.mp3') }}" hidden></audio>
        <audio id="q" src="{{ URL::asset('storage/suara_mp3/Q.mp3') }}" hidden></audio>
        <audio id="r" src="{{ URL::asset('storage/suara_mp3/R.mp3') }}" hidden></audio>
        <audio id="s" src="{{ URL::asset('storage/suara_mp3/S.mp3') }}" hidden></audio>
        <audio id="t" src="{{ URL::asset('storage/suara_mp3/T.mp3') }}" hidden></audio>
        <audio id="u" src="{{ URL::asset('storage/suara_mp3/U.mp3') }}" hidden></audio>
        <audio id="v" src="{{ URL::asset('storage/suara_mp3/V.mp3') }}" hidden></audio>
        <audio id="w" src="{{ URL::asset('storage/suara_mp3/W.mp3') }}" hidden></audio>
        <audio id="x" src="{{ URL::asset('storage/suara_mp3/X.mp3') }}" hidden></audio>
        <audio id="y" src="{{ URL::asset('storage/suara_mp3/Y.mp3') }}" hidden></audio>
        <audio id="z" src="{{ URL::asset('storage/suara_mp3/Z.mp3') }}" hidden></audio>

        @foreach ($antrians as $l)
            <audio id="{{ strtolower(str_replace(' ', '_', $l['nama_layanan'])) }}"
                src='{{ URL::asset('storage/suara_mp3/' . strtolower(str_replace(' ', '_', $l['nama_layanan'])) . '.mp3') }}'
                hidden></audio>
        @endforeach

    </div>
@endsection
@section('script')
    {{-- <script src="https://code.responsivevoice.org/responsivevoice.js?key=JQg5IHCc"></script> --}}
    <script type="text/javascript">
        // Testing Suara tanpa harus panggil dari login admin

        // Suara Opening
        // $("#opening")[0].play()

        // var time_opening = parseInt($("#opening")[0].duration.toString()
        //     .replace(".", "").slice(0, -1))

        // var time_nomor_antrian = parseInt($("#nomor_antrian")[0].duration
        //     .toString().replace(".", ""))

        // var antrian = "TA123"
        // var array_antrian = antrian.split("")
        // var time_huruf_1 = parseInt($("#" + array_antrian[0].toLowerCase())[0]
        //     .duration
        //     .toString().replace(".", "")) + 1000
        // var time_huruf_2 = parseInt($("#" + array_antrian[1].toLowerCase())[0]
        //     .duration
        //     .toString().replace(".", "")) + 500
        // var time_huruf_3 = parseInt($("#" + array_antrian[2])[0].duration
        //     .toString().replace(".", "")) + 500
        // var time_huruf_4 = parseInt($("#" + array_antrian[3])[0].duration
        //     .toString().replace(".", "")) + 500
        // var time_huruf_5 = parseInt($("#" + array_antrian[4])[0].duration
        //     .toString().replace(".", "")) + 1000

        // var time_ke_loket = parseInt($("#ke_loket")[0].duration.toString()
        //     .replace(".", "").slice(0, -1)) + 500

        // var time_nama_layanan = parseInt($("#pengembalian_barang_bukti")[0].duration.toString()
        //     .replace(".", "").slice(0, -1)) + 1000

        // // Suara Antrian Nomor
        // setTimeout(function nomor_antrian() {
        //     $("#nomor_antrian")[0].play()
        // }, time_opening);

        // // Suara String 1
        // setTimeout(function numberIndex() {
        //     $("#" + array_antrian[0].toLowerCase())[0].play()
        // }, parseInt(time_opening) + parseInt(time_nomor_antrian));

        // // Suara String 2
        // setTimeout(function numberIndex() {
        //     $("#" + array_antrian[1].toLowerCase())[0].play()
        // }, parseInt(time_opening) + parseInt(time_nomor_antrian) + parseInt(time_huruf_1));

        // // Suara String 3
        // setTimeout(function numberIndex() {
        //         $("#" + array_antrian[2])[0].play()
        //     }, parseInt(time_opening) + parseInt(time_nomor_antrian) + parseInt(time_huruf_1) +
        //     parseInt(time_huruf_2));

        // // Suara String 4
        // setTimeout(function numberIndex() {
        //         $("#" + array_antrian[3])[0].play()
        //     }, parseInt(time_opening) + parseInt(time_nomor_antrian) + parseInt(time_huruf_1) +
        //     parseInt(time_huruf_2) + parseInt(time_huruf_3));

        // // Suara String 5
        // setTimeout(function numberIndex() {
        //         $("#" + array_antrian[4])[0].play()
        //     }, parseInt(time_opening) + parseInt(time_nomor_antrian) + parseInt(time_huruf_1) +
        //     parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(time_huruf_4));

        // // Suara Ke Loket
        // setTimeout(function nomor_antrian() {
        //         $("#ke_loket")[0].play()
        //     }, parseInt(time_opening) + parseInt(time_nomor_antrian) + parseInt(time_huruf_1) +
        //     parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(time_huruf_4) + parseInt(
        //         time_huruf_5));

        // var e = []
        // e['nama_layanan'] = 'Pengembalian Tilang'

        // // Suara Nama Layanan
        // setTimeout(function nomor_antrian() {
        //         $("#" + e['nama_layanan'].replace(" ", "_").toLowerCase())[0].play()
        //     }, parseInt(time_opening) + parseInt(time_nomor_antrian) + parseInt(time_huruf_1) +
        //     parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(time_huruf_4) + parseInt(
        //         time_huruf_5) +
        //     parseInt(time_ke_loket));

        // // Suara Penutup
        // setTimeout(function nomor_antrian() {
        //         $("#ending")[0].play()
        //     }, parseInt(time_opening) + parseInt(time_nomor_antrian) + parseInt(time_huruf_1) +
        //     parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(time_huruf_4) + parseInt(
        //         time_huruf_5) +
        //     parseInt(time_ke_loket) + parseInt(time_nama_layanan));

        var satker_id = $('#satker_id').val()
        $(function() {
            $.ajax({
                type: "get",
                url: "{{ url('video.play') }}/" + satker_id,
                success: function(data) {
                    if (data['video'].length > 0) {
                        data.video.forEach(function callback(e, index) {
                            autoplay = (index == 0) ? "autoplay" : ""
                            active = (index == 0) ? "active" : ""
                            $('.video_player').append(`
                                    <div class="carousel-item ` + active + `">
                                        <video id="video_play` + index +
                                `" width="100%" muted controls autoplay="` +
                                autoplay + `" style="object-fit: fill;" >
                                            <source src="` + e['video'] + `" type="video/mp4">
                                        </video>
                                    </div>
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
                        $('.video_player').append(`
                            <center class="p-4" style="font-color:yellow; font-size:16px; background-color:white">
                                <label> Data Video belum diaktifkan</label>
                            </center>
                        `)
                    }
                }
            })

            $.ajax({
                url: "{{ url('banner_play') }}?category_id=1&satker_id=" + satker_id,
                success: function(data) {
                    if (data['video'].length > 0) {
                        data.video.forEach(function callback(e, index) {
                            active = (index == 0) ? "active" : ""
                            $('#card_banner').append(`
                                    <div class="carousel-item ` + active + `">
                                        <img class="d-block mx-auto" src="` + e['banner'] + `" width="100%" height="230px" style="background-size: 100%;">
                                    </div>
                                `)
                        })
                    } else {
                        $('#card_banner').append(`
                            <center class="p-4" style="font-color:yellow; font-size:16px; background-color:white">
                                <label> Data Video belum diaktifkan</label>
                            </center>
                        `)
                    }
                }
            })

            if ($('#antrian').val() != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                $("#exampleModal").modal('show')

                fd = new FormData()
                fd.append('id', $('#antrian').val())
                $.ajax({
                    type: "post",
                    url: "{{ route('guest.pdf') }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var blob = new Blob([data]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "Antrian_PDF.pdf";
                        link.click();
                        location.reload();
                    }
                }).done(function() { //use this
                    $('#exampleModal').modal('hide')
                })
            }
        })

        $(document).ready(function() {
            setInterval(function() {
                daftar_layanan()
            }, 2000);
        });

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

                                // Suara Opening
                                $("#opening")[0].play()

                                var time_opening = parseInt($("#opening")[0].duration.toString()
                                    .replace(".", "").slice(0, -1))

                                var time_nomor_antrian = parseInt($("#nomor_antrian")[0].duration
                                    .toString().replace(".", ""))

                                var array_antrian = e['antrian_saat_ini']

                                var time_huruf_1 = parseInt($("#" + array_antrian[0].toLowerCase())[0]
                                    .duration
                                    .toString().replace(".", "")) + 1000
                                var time_huruf_2 = parseInt($("#" + array_antrian[1].toLowerCase())[0]
                                    .duration
                                    .toString().replace(".", "")) + 500
                                var time_huruf_3 = parseInt($("#" + array_antrian[2])[0].duration
                                    .toString().replace(".", "")) + 500
                                var time_huruf_4 = parseInt($("#" + array_antrian[3])[0].duration
                                    .toString().replace(".", "")) + 500
                                var time_huruf_5 = parseInt($("#" + array_antrian[4])[0].duration
                                    .toString().replace(".", "")) + 500

                                var time_ke_loket = parseInt($("#ke_loket")[0].duration.toString()
                                    .replace(".", "").slice(0, -1)) + 500

                                var time_nama_layanan = parseInt($("#" + e['nama_layanan'].replaceAll(
                                    " ", "_").toLowerCase())[0].duration.toString().replace(".",
                                    "").slice(0, -1)) + 1000

                                // Suara Antrian Nomor
                                setTimeout(function nomor_antrian() {
                                    $("#nomor_antrian")[0].play()
                                }, time_opening);

                                // Suara String 1
                                setTimeout(function numberIndex() {
                                    $("#" + array_antrian[0].toLowerCase())[0].play()
                                }, parseInt(time_opening) + parseInt(time_nomor_antrian));

                                // Suara String 2
                                setTimeout(function numberIndex() {
                                        $("#" + array_antrian[1].toLowerCase())[0].play()
                                    }, parseInt(time_opening) + parseInt(time_nomor_antrian) +
                                    parseInt(time_huruf_1));

                                // Suara String 3
                                setTimeout(function numberIndex() {
                                        $("#" + array_antrian[2])[0].play()
                                    }, parseInt(time_opening) + parseInt(time_nomor_antrian) +
                                    parseInt(time_huruf_1) +
                                    parseInt(time_huruf_2));

                                // Suara String 4
                                setTimeout(function numberIndex() {
                                        $("#" + array_antrian[3])[0].play()
                                    }, parseInt(time_opening) + parseInt(time_nomor_antrian) +
                                    parseInt(time_huruf_1) +
                                    parseInt(time_huruf_2) + parseInt(time_huruf_3));

                                // Suara String 5
                                setTimeout(function numberIndex() {
                                        $("#" + array_antrian[4])[0].play()
                                    }, parseInt(time_opening) + parseInt(time_nomor_antrian) +
                                    parseInt(time_huruf_1) +
                                    parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(
                                        time_huruf_4));

                                // Suara Ke Loket
                                setTimeout(function nomor_antrian() {
                                        $("#ke_loket")[0].play()
                                    }, parseInt(time_opening) + parseInt(time_nomor_antrian) +
                                    parseInt(time_huruf_1) +
                                    parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(
                                        time_huruf_4) + parseInt(time_huruf_5));

                                // Suara Nama Layanan
                                setTimeout(function nomor_antrian() {
                                        $("#" + e['nama_layanan'].replaceAll(" ", "_")
                                            .toLowerCase())[
                                            0].play()
                                    }, parseInt(time_opening) + parseInt(time_nomor_antrian) +
                                    parseInt(time_huruf_1) +
                                    parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(
                                        time_huruf_4) + parseInt(time_huruf_5) +
                                    parseInt(time_ke_loket));

                                // Suara Penutup
                                setTimeout(function nomor_antrian() {
                                        $("#ending")[0].play()
                                    }, parseInt(time_opening) + parseInt(time_nomor_antrian) +
                                    parseInt(time_huruf_1) +
                                    parseInt(time_huruf_2) + parseInt(time_huruf_3) + parseInt(
                                        time_huruf_4) + parseInt(
                                        time_huruf_5) +
                                    parseInt(time_ke_loket) + parseInt(time_nama_layanan));

                                $(".ubah_data" + index).remove()
                                $('.tambah_data' + index).append(`
                                            <h3 style="color:#FFF; font-size: 40px;" class="ubah_data` + index + `">` +
                                    e['antrian_saat_ini'] + `</h3>
                                    `)
                                localStorage.setItem(e['kode_layanan'] + tgl, e['antrian_saat_ini'])
                            }
                        })
                    }
                }
            });
        }

        startup()

        var dt = new Date()
        var tgl = dt.getFullYear() + '-' + dt.getMonth() + '-' + dt.getDate();

        function startup() {
            $.ajax({
                type: "get",
                url: "{{ url('data_antrian') }}/" + satker_id,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result['data'].length > 0) {
                        result.data.forEach(function callback(e, index) {
                            $(".remove" + index).remove()
                            var col = (index / 2 % 1) ? "bg-success" : ""
                            var result = localStorage.getItem(e['kode_layanan'] + tgl) ?? e[
                                'antrian_saat_ini']
                            var img = e['icon'] !== null ?
                                `<img src="` + e['icon'] +
                                `" alt="" class="avatar-sm rounded-circle" style="height:50%">` :
                                `<img src="{{ asset('assets/images/logo/kejaksaan-logo.png') }}" class="avatar-sm rounded-circle" style="height:50%">`
                            $('#card_layanan').append(`
                                    <div class="remove` + index + ` ` + col +
                                `">
                                        <table width="100%">
                                            <tr>
                                                <td width="40%">
                                                    <h3 class="text-uppercase text-center" style="color:#FFF; padding:10px; font-size: 40px;">` +
                                e[
                                    'kode_layanan'] + `</h3>
                                                </td>
                                                <td width="20%" class="text-center">
                                                    <h3><i class=" ri-play-mini-fill" style="color:#FFFF00;  font-size: 40px;"></i></h3>
                                                </td>
                                                <td width="40%" class="text-center tambah_data` + index + `">
                                                    <h3 style="color:#FFF; font-size: 40px;" class="ubah_data` +
                                index + `">` + result + `</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                `)

                            if (localStorage.getItem(e['kode_layanan'] + tgl) == null)
                                localStorage.setItem(e['kode_layanan'] + tgl, e['antrian_saat_ini'])
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

        // waktu
        setInterval(() => {
            var dt = new Date()
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds()
            if (dt.getMinutes() + ":" + dt.getSeconds() == "0:0" || dt.getMinutes() + ":" + dt.getSeconds() ==
                "30:0") location.reload()
            $("#waktu").text(time)
        }, 1000);

        setInterval(() => {
            $('#video_play0').on('stop', function() {
                $('#video_play0')[0].play()
            });
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
    </script>
@endsection
