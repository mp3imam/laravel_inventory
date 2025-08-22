@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')
    @include('sweetalert::alert')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="row mt-4">
                            <div class="col-xxl-4 col-md-4 mb-3">
                                <label>Filter Provinsi</label>
                                @if($satker->provinsi_id != null)
                                    <select id='provinsi' name="provinsi"></select>
                                @else
                                <select id='provinsi_id' name="provinsi_id[]" multiple="multiple"></select>
                                @endif
                            </div>
                            <div class="col-xxl-4 col-md-4 mb-3">
                                <input id="user_role" value="{{ $satker->roles[0]->name }}" hidden>
                                <input id="user_id" value="{{ $satker->id }}" hidden>
                                @if($satker->roles[0]->name != 'super-admin')
                                    <input id="satker_role_id" value="{{ $satker->satker ? $satker->satker_id : $satker->id }}" hidden>
                                @endif
                                <label>Filter Satker</label>
                                @if($satker->satker_id != null)
                                    <select id='satker' name="satker">
                                    </select>
                                @else
                                    <select id='satker_id' name="satker_id[]" multiple="multiple">
                                    </select>
                                @endif
                            </div>
                            <div class="col-xxl-4 col-md-4 mb-3">
                                <label>Filter Layanan</label>
                                <select id='layanan_id' name="layanan_id[]" multiple="multiple"
                                    class="form-select form-control"></select>
                            </div>
                            <div class="col-xxl-4 col-md-4 mb-3">
                                <label>Filter Nomor Antrian</label>
                                <input id='nomor_antrian' name="nomor_antrian" class="form-control" />
                            </div>
                            <div class="col-xxl-4 col-md-4 mb-3">
                                <label>Filter Tanggal Awal</label>
                                <input type="text" id="tanggal_awal" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d-m-Y" data-mindate="{{ \Carbon\Carbon::now()->subWeek(2)->format('d-m-Y') }}" data-maxdate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" value="{{ \Carbon\Carbon::now()->subWeek(1)->format('d-m-Y') }}" readonly="readonly">
                            </div>
                            <div class="col-xxl-4 col-md-4 mb-3">
                                <label>Filter Tanggal Akhir</label>
                                <input type="text" id="tanggal_akhir" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d-m-Y" data-mindate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" data-maxdate="{{ \Carbon\Carbon::now()->addWeek(1)->format('d-m-Y') }}" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                            </div>
                            <div class="col-xxl-4 col-md-4 mb-3">
                                <label>Filter Rating</label>
                                <select id='rating' name="rating" class="form-select form-control">
                                    <option value="">-- Pilih Semua --</option>
                                    <option class="text-muted mx-4" value="Sangat Memuaskan">Sangat Memuaskan</option>
                                    <option class="text-muted mx-4" value="Cukup Memuaskan">Cukup Memuaskan</option>
                                    <option class="text-muted mx-4" value="Memuaskan">Memuaskan</option>
                                    <option class="text-muted mx-4" value="Kurang Memuaskan">Kurang Memuaskan</option>
                                    <option class="text-muted mx-4" value="Tidak Memuaskan">Tidak Memuaskan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="dataTable" class="table table-striped table-bordered table-sm " cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Provinsi</th>
                                        <th>Satker</th>
                                        <th>Layanan</th>
                                        <th>User</th>
                                        <th>No Antrian</th>
                                        <th>Rating</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
@section('script')

    <script type="text/javascript">
        $(function() {
            moment.locale('id');

            var user_role = $('#user_role').val()
            var sakter_id = null
            if (user_role == 'user' || user_role == 'admin') sakter_id = $('#satker_role_id').val()
            var table = $('#dataTable').DataTable({
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list_ratings') }}",
                    data: function(d) {
                        d.question = $('#question').val(),
                        d.provinsi_id = $('#provinsi_id').val(),
                        d.satker_id = $('#satker_id').val(),
                        d.layanan_id = $('#layanan_id').val(),
                        d.nomor_antrian = $('#nomor_antrian').val(),
                        d.tanggal_awal = $('#tanggal_awal').val(),
                        d.tanggal_akhir = $('#tanggal_akhir').val(),
                        d.rating = $('#rating').val()
                    }
                },
                columns: [{
                    data: "id",
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },{
                    data: 'provinsi',
                    name: 'Provinsi'
                }, {
                    data: 'satker',
                    name: 'Satker'
                }, {
                    data: 'layanan',
                    name: 'Layanan'
                }, {
                    data: 'user',
                    name: 'User'
                }, {
                    data: 'antrian',
                    name: 'Nomor Antrian'
                }, {
                    data: 'rating',
                    name: 'Rating'
                }, {
                    data: 'created_at',
                    name: 'Tanggal',
                    render: function(data){
                        return moment(data).format('DD-MM-YYYY')
                    }
                }]
            });

            $('#nomor_antrian').change(function() {
                table.draw();
            });
            $('#provinsi_id').change(function() {
                table.draw();
            });
            $('#satker_id').change(function() {
                table.draw();
            });
            $('#layanan_id').change(function() {
                table.draw();
            });
            $('#user_id').change(function() {
                table.draw();
            });
            $('#tanggal_awal').change(function() {
                table.draw();
            });
            $('#tanggal_akhir').change(function() {
                table.draw();
            });
            $('#rating').change(function() {
                table.draw();
            });
        });

        if (user_role['value'] != 'admin') $('#layanan_id').select2([])


        $('#provinsi_id').select2({
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

        $('#satker_id').select2({
            ajax: {
            url: "{{ route('api.satker') }}",
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

        var satker = "{{ $satker->satker_id }}"
        if (satker) {
            var dataProvinsi = {id: "{{ $satker->provinsi_id !== "" ? $satker->provinsi_id : "" }}",text: "{{ $satker->provinsi_id ? $satker->provinsi->name : "" }}", selected: true};
            var newOptionProvinsi = new Option(dataProvinsi.text, dataProvinsi.id, false, false);
            $('#provinsi').append(newOptionProvinsi).trigger('change');
            $('#provinsi').select2();

            var dataSatker = {id: "{{ $satker->satker_id !== "" ? $satker->satker_id : "" }}",text: "{{ $satker->satker_id ? $satker->satker->name : "" }}", selected: true};
            var newOptionSatker = new Option(dataSatker.text, dataSatker.id, false, false);
            $('#satker').append(newOptionSatker).trigger('change');
            $('#satker').select2();

            url = "{{ url('api/satker') }}?satker="+satker
            var dataSatker = {id: "{{ $satker->satker_id !== "" ? $satker->satker_id : "" }}",text: "{{ $satker->satker_id ? $satker->satker->name : "" }}", selected: true};
            var newOptionSatker = new Option(dataSatker.text, dataSatker.id, false, false)
            $('#satker').append(newOptionSatker).trigger('change')
            $('#satker').select2()

            var satker_id = $("#satker").val()
            $('#layanan_id').select2({
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
        }
    </script>
@endsection
