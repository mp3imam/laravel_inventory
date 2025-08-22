@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body mt-4">
                <div id="customerList">
                    <div class="col-sm-auto">
                        <a type="button" class="btn btn-primary btn-label btn-pdf">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bx bxs-file-pdf label-icon align-middle fs-16 me-2"></i>
                                </div>
                                <div class="flex-grow-1 btn-pdf-loading" hidden>
                                    Loading...
                                </div>
                                <div class="flex-grow-1 btn-pdf-no-loading">
                                    PDF
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="row g-4 mt-3">
                        <div class="row">
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Provinsi</label>
                                @if($satker->provinsi_id != null)
                                    <select id='provinsi' name="provinsi"></select>
                                @else
                                <select id='provinsi_id' name="provinsi_id[]" multiple="multiple"></select>
                                @endif
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Satker</label>
                                @if($satker->satker_id != null)
                                    <select id='satker' name="satker[]"></select>
                                @else
                                    <select id='satker_id' name="satker_id[]" multiple="multiple"></select>
                                @endif
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Layanan</label>
                                <select id='layanan_id' name="layanan_id[]" multiple="multiple"></select>
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Roles</label>
                                <select id='role_id' name="role_id"></select>
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter User</label>
                                <input id='users' name="users" class="form-control"/>
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Tanggal Awal</label>
                                <input type="text" id="tanggal_awal" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d-m-Y" data-mindate="{{ \Carbon\Carbon::now()->subWeek(2)->format('d-m-Y') }}" data-maxdate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" value="{{ \Carbon\Carbon::now()->subWeek(1)->format('d-m-Y') }}" readonly="readonly">
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Tanggal Akhir</label>
                                <input type="text" id="tanggal_akhir" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d-m-Y" data-mindate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" data-maxdate="{{ \Carbon\Carbon::now()->addWeek(1)->format('d-m-Y') }}" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Status</label>
                                <select id='status' name="status" class="form-control" id="choices-single-no-search"
                                    name="choices-single-no-search" data-choices data-choices-search-false
                                    data-choices-removeItem>
                                    <option value=''>Pilih Semua</option>
                                    <option value='0'>{{ \App\Models\AntrianModel::STATUS_MENUNGGU }}</option>
                                    <option value='1'>{{ \App\Models\AntrianModel::STATUS_PROSESS }}</option>
                                    <option value='2'>{{ \App\Models\AntrianModel::STATUS_SELESAI }}</option>
                                    <option value='3'>{{ \App\Models\AntrianModel::STATUS_BATAL }}</option>
                                    <option value='4'>{{ \App\Models\AntrianModel::STATUS_TIDAK_HADIR }}</option>
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
                                        <th>Nomor</th>
                                        <th>Provinsi</th>
                                        <th>Satker</th>
                                        <th>Nomor Antrian</th>
                                        <th>Role</th>
                                        <th>Nama User</th>
                                        <th>Layanan</th>
                                        <th>Tanggal Kehadiran</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" style="display: none;" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" id="modal_content_layanan">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(function () {
        var table = $('#dataTable').DataTable({
            dom: 'lrtip',
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list_report_management_system') }}",
                data: function (d) {
                    d.provinsi_id   = $('#provinsi_id').val(),
                    d.satker_id     = $('#satker_id').val(),
                    d.layanan_id    = $('#layanan_id').val(),
                    d.users         = $('#users').val(),
                    d.role_id       = $('#role_id').val(),
                    d.tanggal_awal  = $('#tanggal_awal').val(),
                    d.tanggal_akhir = $('#tanggal_akhir  ').val(),
                    d.status        = $('#status').val()
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
                    name: 'Provinsi',
                },{
                    data: 'satker',
                    name: 'Satker'
                },{
                    data: 'nomor_antrian',
                    name: 'Nomor Antrian'
                },{
                    data: 'roles',
                    name: 'Nama Role'
                },{
                    data: 'user',
                    name: 'Nama User'
                },{
                    data: 'layanan',
                    name: 'Nama Layanan'
                },{
                    data: 'tanggal_hadir',
                    name: 'Tanggal Kehadiran'
                },{
                    data: 'keterangan',
                    name: 'Keterangan'
                },{
                    data: 'status',
                    name: 'Status'
                }
            ]
        });

        $('#provinsi_id').change(function () {
            table.draw();
        });

        $('#satker_id').change(function () {
            table.draw();
        });

        $('#layanan_id').change(function () {
            table.draw();
        });

        $('#status').change(function () {
            table.draw();
        });

        $('#role_id').change(function () {
            table.draw();
        });

        $('#users').change(function () {
            table.draw();
        });

        $('#tanggal_awal').change(function () {
            table.draw()
        });

        $('#tanggal_akhir').change(function () {
            table.draw()
        });

    });

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
    }

    $('#layanan_id').select2({
        ajax: {
          url: "{{ route('api.layanan') }}",
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

    $('#role_id').select2({
        ajax: {
          url: "{{ route('api.role_users') }}",
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

    $('.btn-pdf').on('click', function(){
        $('#modal_content_layanan').html(`
            <div class="modal-body text-center p-5">
                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px"></lord-icon>

                <div class="mt-4">
                    <h4 class="mb-3">Sedang Mendownload PDF</h4>
                    <p class="text-muted mb-4"> Mohon Tunggu Sebentar. </p>
                </div>
            </div>
        `)
        $("#exampleModal").modal('show');

        var fd = new FormData()
        var satker_id = $('#satker_id').val() ?? $('#satker').val()
        fd.append('satker_id', satker_id)
        fd.append('layanan_id', $('#layanan_id').val())
        fd.append('users', $('#users').val())
        fd.append('role_id', $('#role_id').val())
        fd.append('tanggal_awal', $('#tanggal_awal').val())
        fd.append('tanggal_akhir', $('#tanggal_akhir').val())
        fd.append('status', $('#status').val())
        $.ajax({
            type:'post',
            url: "{{ route('report_management_system.pdf') }}",
            data: fd,
            processData: false,
            contentType: false,
            xhrFields: {
                responseType: 'blob' // to avoid binary data being mangled on charset conversion
            },
            success: function(blob, status, xhr) {
                // check for a filename
                var filename = "";
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }

                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);

                    if (filename) {
                        // use HTML5 a[download] attribute to specify filename
                        var a = document.createElement("a");
                        // safari doesn't support this yet
                        if (typeof a.download === 'undefined') {
                            window.location.href = downloadUrl;
                        } else {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                        }
                    } else {
                        window.location.href = downloadUrl;
                    }

                    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                }
            }
        }).done(function() { //use this
            $('#exampleModal').modal('hide')
        });
    })

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
