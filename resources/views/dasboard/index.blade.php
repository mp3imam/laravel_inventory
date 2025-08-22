@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/swiper/swiper.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
    <div class="row">
        <div class="col-xxl-12 order-xxl-0 order-first">
            <div class="d-flex flex-column h-100">
                <div class="row h-100">
                    <div class="col-lg-6 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Monitor KiosK
                                        </p>
                                    </div>
                                </div>
                                <div id="chart" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    data-colors="[&quot;--vz-primary&quot;, &quot;--vz-info&quot;, &quot;--vz-warning&quot;, &quot;--vz-success&quot;]"
                                    class="apex-charts mt-2" dir="ltr" style="min-height: 202.7px;">
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Admin Layanan
                                        </p>
                                    </div>
                                </div>
                                <div id="admin_layanan" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    data-colors="[&quot;--vz-primary&quot;, &quot;--vz-info&quot;, &quot;--vz-warning&quot;, &quot;--vz-success&quot;]"
                                    class="apex-charts mt-2" dir="ltr" style="min-height: 202.7px;">
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div>
                    {{-- <div class="col-lg-4 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Rating Satker
                                            Terbaik
                                        </p>
                                    </div>
                                </div>
                                <div id="top_rating"
                                    data-colors="[&quot;--vz-primary&quot;, &quot;--vz-info&quot;, &quot;--vz-warning&quot;, &quot;--vz-success&quot;]"
                                    class="apex-charts mt-2" dir="ltr" style="min-height: 202.7px;">
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div> --}}
                </div><!-- end row -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Jumlah Pengunjung disetiap Satker hari ini</h4>
                                <span id="total_tamu" class="text-success text-end"></span>
                            </div>
                            <div class="card-body p-0 pb-3">
                                <div id="chart_tamu" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    data-colors="[&quot;--vz-success&quot;, &quot;--vz-danger&quot;]" class="apex-charts"
                                    dir="ltr" style="min-height: 309px;">
                                </div>
                            </div><!-- end cardbody -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" id="modal_content_layanan">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@section('script')
    <script type="text/javascript">
        var kiosk_aktif = "{{ $kiosk_aktif }}"
        var satker_aktif = "{{ $satker_aktif }}"
        var jumlah_satker = "{{ $jumlah_satker }}"
        var satker_tamu = "{{ $satker_tamu }}"
        var jumlah_tamu = "{{ $jumlah_tamu }}"
        satker_tamu = JSON.parse(satker_tamu.replace(/&quot;/g, '"').replace(/Kejari/g, "").replace(/Kota/g, "").replace(
            /Kabupaten/g, "").replace(/Kejati/g, ""))
        jumlah_tamu = JSON.parse(jumlah_tamu.replace(/&quot;/g, '"'))
        var total_tamu = jumlah_tamu.reduce(function(a, b) {
            return a + b;
        }, 0);
        $('#total_tamu').text(total_tamu + ' orang')
        // console.log(satker_tamu)

        // jumlah_tamu = [
        //     33, 35, 37, 40, 30, 36, 37, 40, 48, 43, 37, 45, 34, 34, 39, 49, 57, 53, 38, 29, 39, 48, 33, 36,
        //     23, 35, 37, 27, 30, 36, 37, 48, 30, 43, 33, 45, 24, 34, 39, 49, 52, 53, 38, 29, 39, 48, 26, 36,
        //     23, 35, 37
        // ]
        // kiosk_aktif = 57
        // satker_aktif = 50
        // jika nilai pembagi belakang koma maka
        // kiosk_aktif = Math.floor(parseInt(kiosk_aktif) / 59 * 100)
        // kiosk_aktif = Math.round(parseInt(kiosk_aktif) / jumlah_satker * 100)

        // chart monitor kiosk
        var options = {
            series: [parseInt(kiosk_aktif), jumlah_satker - parseInt(kiosk_aktif)],
            chart: {
                width: 300,
                type: 'donut',
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        var status = config.dataPointIndex == 0 ? "Aktif" : "Tidak Aktif"
                        var color = config.dataPointIndex == 0 ? "text-success" : "text-danger"
                        $('#modal_content_layanan').html(
                            `
                                <div class="modal-header">
                                    <h5 class="modal-title ` + color + `">KiosK ` + status +
                            `</h5><span class="text-right h5">` +
                            name + `</span>
                                </div>
                                <div class="modal-body">
                                    <table id="dataModal" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Nama Provinsi</th>
                                                <th>Nama Satker</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                `)
                        $('#dataModal').DataTable({
                            dom: 'tip',
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('list_status_kiosk') }}/?status=" + config.dataPointIndex
                            },
                            columns: [{
                                data: 'provinsis',
                                name: 'Nama Provinsi',
                                orderable: false
                            }, {
                                data: 'name',
                                name: 'Nama Satker',
                                orderable: false
                            }]
                        });
                    }
                },
            },
            labels: ['Aktif', 'Tidak Aktif'],
            colors: ['#00bd9d', '#f06548'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    }
                },

                legend: {
                    position: 'bottom'
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // chart Admin Membuka Booking Layanan
        var options = {
            series: [parseInt(satker_aktif), jumlah_satker - parseInt(satker_aktif)],
            chart: {
                width: 300,
                type: 'donut',
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        var status = config.dataPointIndex == 0 ? "Aktif" : "Tidak Aktif"
                        var color = config.dataPointIndex == 0 ? "text-success" : "text-danger"
                        $('#modal_content_layanan').html(
                            `
                                <div class="modal-header">
                                    <h5 class="modal-title ` + color + `">Admin ` + status +
                            `</h5><span class="text-right h5">` +
                            name + `</span>
                                </div>
                                <div class="modal-body">
                                    <table id="dataModalAdmin" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Nama Provinsi</th>
                                                <th>Nama Satker</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                `)
                        $('#dataModalAdmin').DataTable({
                            dom: 'tip',
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('list_status_admin') }}/?status=" + config.dataPointIndex
                            },
                            columns: [{
                                data: 'provinsis',
                                name: 'Nama Provinsi',
                                orderable: false
                            }, {
                                data: 'name',
                                name: 'Nama Satker',
                                orderable: false
                            }]
                        });
                    }
                },
            },
            labels: ['Aktif', 'Tidak Aktif'],
            colors: ['#00bd9d', '#f06548'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#admin_layanan"), options);
        chart.render();

        // chart jumlah tamu disetiap satker
        var options = {
            series: [{
                data: jumlah_tamu,
            }],
            chart: {
                height: 400,
                type: 'area',
                events: {
                    click(event, chartContext, config) {
                        $('#modal_content_layanan').html(
                            `
                                <div class="modal-header">
                                    <h5 class="modal-title text-success">Jumlah Layanan</span>
                                </div>
                                <div class="modal-body">
                                    <table id="dataModalAdmin" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="40%">Nama Satker</th>
                                                <th width="40%">Nama Layanan</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                `)
                        $('#dataModalAdmin').DataTable({
                            dom: 'tip',
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('list_detail_pengunjung') }}/?satker_id=" + (config
                                    .dataPointIndex + 1)
                            },
                            columns: [{
                                data: 'satker',
                                name: 'Nama Satker',
                                orderable: false
                            }, {
                                data: 'layanan',
                                name: 'Nama Satker',
                                orderable: false
                            }, {
                                data: 'jumlah_layanan',
                                name: 'Jumlah Layanan',
                                orderable: false
                            }]
                        });
                    }
                }
            },
            xaxis: {
                categories: satker_tamu,
                labels: {
                    // position: 'center',
                    style: {
                        color: '#17A589',
                        align: 'right',
                        fontSize: '14px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 400,
                        cssClass: 'apexcharts-yaxis-label',
                    },
                    // offsetY: 3,
                    rotate: -90
                }
            },
            yaxis: {
                opposite: true
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart_tamu"), options);
        chart.render();
    </script>
@endsection
