@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div id="customerList">
                    <div class="col-sm-auto mb-3">
                        @if ($role == "admin")
                            <a type="button" href="{{ route('rate_support_sistem.create') }}"
                                class="btn btn-success btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-user-plus label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Tambah
                                    </div>
                                </div>
                            </a>
                            @endif
                    </div>
                    <div class="row g-4">
                        <div class="row mt-4">
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Nomor Keluhan</label>
                                <input id='nomor_keluhan' name="nomor_keluhan" placeholder="Nomor Atau Pertanyaan" class="form-control">
                            </div>
                            @if ($role == "super-admin")
                                <div class="col-xxl-3 col-md-4 mb-3">
                                    <label class="form-label">Filter Satker</label>
                                    <select id='satker_id' name="satker_id[]" multiple="multiple">
                                    </select>
                                </div>
                            @endif
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Tanggal Awal</label>
                                <input type="text" id="tanggal_awal" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d-m-Y" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Tanggal Akhir</label>
                                <input type="text" id="tanggal_akhir" class="form-control flatpickr-input active" data-provider="flatpickr" data-date-format="d-m-Y" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Status</label>
                                <select id='status' name="status" class="form-control" data-choices>
                                    <option value='' selected>Pilih Semua</option>
                                    <option value="0">Belum Terjawab</option>
                                    <option value="1">Sudah Terjawab</option>
                                    <option value="2">Selesai</option>
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
                                        <th width="10%">No</th>
                                        <th width="10%">Nomor</th>
                                        <th width="20%">Nama Satker</th>
                                        <th width="20%">Nama User</th>
                                        <th width="30%">Pertanyaan</th>
                                        <th width="30%">Gambar</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Rating</th>
                                        <th width="10%">Tanggal</th>
                                        <th>Action</th>
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
                url: "{{ route('list_rate_support_sistem') }}",
                data: function (d) {
                    d.satker_id = $('#satker_id').val(),
                    d.nomor_keluhan = $('#nomor_keluhan').val(),
                    d.tanggal = $('#tanggal').val(),
                    d.status = $('#status').val()
                }
            },
            columns: [{
                    data: "id",
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },{
                    data: 'nomor_keluhan',
                    name: 'Nomor Keluhan'
                },{
                    data: 'satker',
                    name: 'Satker'
                },{
                    data: 'user',
                    name: 'User'
                },{
                    data: 'pertanyaan',
                    name: 'Pertanyaan'
                },{
                    data: 'image',
                    name: 'Image',
                    sortable: false,
                    render: function (data, type, full, meta) {
                        if (data == null) return "";
                        return "<img src=\"" + data + "\" height=\"50\" widht=\"50\" />";
                    },
                },{
                    data: 'statuss',
                    name: 'Status'
                },{
                    data: 'ratings',
                    name: 'Rating'
                },{
                    data: 'created_at',
                    name: 'Tanggal'
                },{
                    data: 'action',
                    name: 'Action',
                    sortable: false
                }
            ]
        });

        $('#nomor_keluhan').change(function () {
            table.draw();
        });

        $('#satker_id').change(function () {
            table.draw();
        });

        $('#tanggal_awal').change(function () {
            table.draw()
        });

        $('#tanggal_akhir').change(function () {
            table.draw()
        });

        $('#status').change(function () {
            table.draw();
        });
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

    function alert_delete(id, nama){
        Swal.fire({
        title: `Hapus Data Layanan`,
        text: "Apakah anda yakin untuk menghapus data Layanan '"+nama+"'",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ url('layanans') }}" + '/' + id,
                    data: {
                        "_method": 'delete',
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (result) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Your file has been deleted.',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(function(){
                            location.reload();
                        });
                    }
                });
            }

        });
    }

    $('.btn-pdf').on('click', function(){
        $('.btn-pdf-loading').attr('hidden',false)
        $('.btn-pdf-no-loading').attr('hidden',true)
        var fd = new FormData()
        fd.append('satker_id', $('#satker_id').val())
        fd.append('layanan_id', $('#layanan_id').val())
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
        })
        $('.btn-pdf-loading').attr('hidden',true)
        $('.btn-pdf-no-loading').attr('hidden',false)
    })

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
