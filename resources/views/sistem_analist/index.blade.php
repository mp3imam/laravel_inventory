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
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Analisa Pengunjung disetiap Satker PerLayanan Tahun
                                    {{ date('Y') }}</h4>
                                <span id="total_tamu" class="text-success text-end"></span>
                            </div>
                            <div class="card-body p-0 pb-3">
                                <div id="chart_tamu" data-colors="[&quot;--vz-success&quot;, &quot;--vz-danger&quot;]"
                                    class="apex-charts" dir="ltr" style="min-height: 309px;" data-bs-toggle="modal"
                                    data-bs-target=".bs-example-modal-xl">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bs-example-modal-xl" tabindex="-1" aria-labelledby="myExtraLargeModalLabel"
                    aria-modal="true" role="dialog" style="additive-symbols: 0px;" data-bs-backdrop="static">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" id="chart_jumlah_tamu_persatker">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@section('script')
    <script type="text/javascript">
        var options = {
            series: {!! json_encode($finalData) !!},
            chart: {
                type: 'bar',
                height: 430,
                stacked: true,
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        var bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                            'September', 'Oktober', 'November', 'Desember'
                        ]
                        $('#chart_jumlah_tamu_persatker').html(
                            `
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myExtraLargeModalLabel">Jumlah Satker Perbulan ` +
                            bulan[config.dataPointIndex + 1] + `</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="chart_satker_perbulan">
                                </div>
                            `)
                        $.ajax({
                            url: "{{ route('jumlah_pengunjung_satker_perbulan') }}?bulan=" + config
                                .dataPointIndex,
                            success: function(result) {
                                var options = {
                                    series: result['data'],
                                    chart: {
                                        type: 'bar',
                                        height: 530,
                                        weight: 400,
                                        stacked: true,
                                        toolbar: {
                                            show: true
                                        },
                                        zoom: {
                                            enabled: true
                                        }
                                    },
                                    responsive: [{
                                        breakpoint: 480,
                                        options: {
                                            legend: {
                                                position: 'bottom',
                                                offsetX: -10,
                                                offsetY: 0
                                            }
                                        }
                                    }],
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            borderRadius: 10,
                                            dataLabels: {
                                                total: {
                                                    enabled: true,
                                                    style: {
                                                        fontSize: '13px',
                                                        fontWeight: 900
                                                    }
                                                }
                                            }
                                        },
                                    },
                                    xaxis: {
                                        categories: result['satkers'],
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
                                    legend: {
                                        position: 'right',
                                        offsetY: 40
                                    },
                                    fill: {
                                        opacity: 1
                                    }
                                };

                                var chart = new ApexCharts(document.querySelector(
                                    "#chart_satker_perbulan"), options);
                                chart.render();
                            }
                        });
                        $('#chart_jumlah_tamu_persatker').on('show')
                    }
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 10,
                    dataLabels: {
                        total: {
                            enabled: true,
                            style: {
                                fontSize: '13px',
                                fontWeight: 900
                            }
                        }
                    }
                },
            },
            title: {
                text: 'Perbulan',
                offsetX: 8,
                style: {
                    fontSize: '20px',
                },
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                    'Des'
                ],
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart_tamu"), options);
        chart.render();
    </script>
@endsection
