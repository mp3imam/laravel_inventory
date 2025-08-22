@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')
    @include('sweetalert::alert')
    <div class="row">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div id="customerList">
                        <div class="row g-4">
                            <div class="col-sm-auto mb-3">
                                <a type="button" href="{{ route('booking.add') }}" class="btn btn-success btn-label">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="bx bx-user-plus label-icon align-middle fs-16 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Tambah
                                        </div>
                                    </div>
                                </a>
                                @if ($satker->roles[0]->name != 'user')
                                    <a type="button" class="btn btn-primary btn-label btn-pdf" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
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
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-xxl-4 col-md-4 mb-3">
                                    <input id="user_role" value="{{ $satker->roles[0]->name }}" hidden>
                                    <input id="user_id" value="{{ $satker->id }}" hidden>
                                    @if ($satker->roles[0]->name != 'super-admin')
                                        <input id="satker_role_id"
                                            value="{{ $satker->satker ? $satker->satker_id : $satker->id }}" hidden>
                                    @endif
                                    <label>Filter Satker</label>
                                    @if ($satker->satker_id != null)
                                        <select id='satker' name="satker">
                                        </select>
                                    @else
                                        <select id='satker_id' name="satker_id[]" multiple="multiple">
                                        </select>
                                    @endif
                                </div>
                                <div class="col-xxl-4 col-md-4 mb-3">
                                    <label>Filter Layanan</label>
                                    <select id='layanan_id' name="layanan_id[]" multiple="multiple" disabled>
                                    </select>
                                </div>
                                <div class="col-xxl-4 col-md-4 mb-3">
                                    <label>Filter Status</label>
                                    <select id='status' name="status" class="form-control" id="choices-single-no-search"
                                        name="choices-single-no-search" data-choices data-choices-search-false
                                        data-choices-removeItem>
                                        <option value=''>Pilih Semua</option>
                                        <option value='0'>{{ App\Models\AntrianModel::STATUS_MENUNGGU }}</option>
                                        <option value='1'>{{ App\Models\AntrianModel::STATUS_PROSESS }}</option>
                                        <option value='2'>{{ App\Models\AntrianModel::STATUS_SELESAI }}</option>
                                        <option value='3'>{{ App\Models\AntrianModel::STATUS_BATAL }}</option>
                                        <option value='4'>{{ App\Models\AntrianModel::STATUS_TIDAK_HADIR }}</option>
                                    </select>
                                </div>
                                <div class="col-xxl-4 col-md-4 mb-3">
                                    <label>Filter Tanggal Awal</label>
                                    <input type="text" id="tanggal_awal" class="form-control flatpickr-input active"
                                        data-provider="flatpickr" data-date-format="d-m-Y"
                                        data-mindate="{{ \Carbon\Carbon::now()->subDays(1)->format('d-m-Y') }}"
                                        data-maxdate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}"
                                        value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                                </div>
                                <div class="col-xxl-4 col-md-4 mb-3">
                                    <label>Filter Tanggal Akhir</label>
                                    @php
                                        $day = 1;
                                        if (date('D') == 'Fri') {
                                            $day = 3;
                                        }
                                    @endphp
                                    <input type="text" id="tanggal_akhir" class="form-control flatpickr-input active"
                                        data-provider="flatpickr" data-date-format="d-m-Y"
                                        data-mindate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}"
                                        data-maxdate="{{ \Carbon\Carbon::now()->addDays($day)->format('d-m-Y') }}"
                                        value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table id="dataTable" class="table table-striped table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="20%">Provinsi</th>
                                            <th width="80px">Satker</th>
                                            <th width="20%">Nama User</th>
                                            <th width="20%">Layanan</th>
                                            <th width="20%">Tanggal Kehadiran</th>
                                            <th width="20%">Keterangan</th>
                                            <th width="20%">Nomor Antrian</th>
                                            <th width="20%">Status</th>
                                            <th width="20%">Foto</th>
                                            <th width="250px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static"
                        data-bs-keyboard="false" style="display: none;" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
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
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var user_role = $('#user_role').val()
            var sakter_id = null
            if (user_role == 'user' || user_role == 'admin') sakter_id = $('#satker_role_id').val()
            var table = $('#dataTable').DataTable({
                dom: 'lrtip',
                scrollY: "400px",
                scrollX: true,
                processing: true,
                serverSide: true,
                fixedColumns: {
                    left: 0,
                    right: 1,
                    width: 200,
                    targets: 10
                },
                ajax: {
                    url: "{{ route('listBookingLayanan') }}",
                    data: function(d) {
                        d.satker_id = $('#satker_id').val(),
                            d.layanan_id = $('#layanan_id').val(),
                            d.tanggal_awal = $('#tanggal_awal').val(),
                            d.tanggal_akhir = $('#tanggal_akhir').val(),
                            d.user_role = sakter_id,
                            d.status = $('#status').val()
                    }
                },
                columns: [{
                    data: "id",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'provinsi',
                    name: 'Provinsi'
                }, {
                    data: 'satker',
                    name: 'Satker'
                }, {
                    data: 'user',
                    name: 'Nama User'
                }, {
                    data: 'layanan',
                    name: 'Nama Layanan'
                }, {
                    data: 'tanggal_hadir',
                    name: 'Tanggal Kehadiran'
                }, {
                    data: 'keterangan',
                    name: 'Keterangan'
                }, {
                    data: 'nomor_antrian',
                    name: 'Nomor Antrian'
                }, {
                    data: 'status',
                    name: 'Status'
                }, {
                    data: 'image',
                    name: 'foto',
                    render: function(data) {
                        if (!data) return "-";
                        return `<img src="{{ asset('images/`+data+`') }}" width="100px" height="100px" weight="100px">`;
                    }
                }, {
                    data: 'action',
                    name: 'Action',
                    className: "text-center action",
                    sortable: false
                }]
            });

            $('#satker_id').change(function() {
                table.draw();
            });

            $('#layanan_id').change(function() {
                table.draw();
            });

            $('#tanggal_awal').change(function() {
                table.draw();
            });

            $('#tanggal_akhir').change(function() {
                table.draw();
            });

            $('#status').change(function() {
                table.draw();
            });

        });

        if (user_role['value'] != 'admin') $('#layanan_id').select2([])

        var satker = "{{ $satker->satker_id }}"
        var url = "{{ route('api.satker') }}"
        if (satker) {
            url = "{{ url('api/satker') }}?satker=" + satker
            var dataSatker = {
                id: "{{ $satker->satker_id !== '' ? $satker->satker_id : '' }}",
                text: "{{ $satker->satker_id ? $satker->satker->name : '' }}",
                selected: true
            };
            var newOptionSatker = new Option(dataSatker.text, dataSatker.id, false, false)
            $('#satker').append(newOptionSatker).trigger('change')
            $('#satker').select2()

            $('#layanan_id').attr('disabled', false)

            var satker_id = $("#satker").val()
            $('#layanan_id').select2({
                placehoder: "Pilih Layanan",
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

        $('#satker_id').select2({
            placehoder: "Pilih Satker",
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    $("#layanan_id").prop("disabled", false)
                    $('#layanan_id').val('')
                    return {
                        results: $.map(data.data, function(item) {
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

        $('#satker_id').change(function() {
            var satker_id = $(this).val()
            $("#satker").val($("#satker_id option:selected").text())
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
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function cekAntrian(id, nomor_antrian, namaLayanan, status, provinsi_id, satker_id, layanan_id, user_id) {
            Fdata = new FormData()
            Fdata.append('id', id)
            Fdata.append('status', status)
            Fdata.append('nomor_antrian', nomor_antrian)
            Fdata.append('satker_id', satker_id)
            Fdata.append('layanan_id', layanan_id)
            Fdata.append('user_id', user_id)
            $.ajax({
                type: "POST",
                url: "{{ route('booking.cek_proses') }}",
                data: Fdata,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result['message'] == 'Sukses') {
                        fd = new FormData()
                        fd.append('id', id)
                        fd.append('nomor_antrian', nomor_antrian)
                        fd.append('provinsi_id', provinsi_id)
                        fd.append('satker_id', satker_id)
                        fd.append('layanan_id', layanan_id)

                        $.ajax({
                            url: "{{ route('booking.cek_antrian') }}",
                            type: 'post',
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function(result) {
                                result.message == 'Silahkan Masukan Alasan' ?
                                    masukanAlasan(id, nomor_antrian, namaLayanan, status,
                                        provinsi_id, satker_id, layanan_id, user_id) :
                                    panggilAntrian(id, nomor_antrian, namaLayanan, status,
                                        satker_id, layanan_id, user_id)
                            }
                        });
                    } else {
                        Swal.fire(
                            'Antrian Tidak di Proses',
                            'Antrian tidak bisa di proses karena masih ada layanan yang belum di selesaikan',
                            'error'
                        )
                    }
                }
            });
        }

        function masukanAlasan(id, nomor_antrian, namaLayanan, status, satker_id, layanan_id, user_id) {
            Swal.fire({
                title: 'Nomor Antrian Berbeda, silahkan masukan alasannya',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Simpan Alasan',
                showLoaderOnConfirm: true,
                preConfirm: (keterangan) => {
                    fd = new FormData()
                    fd.append('id', id)
                    fd.append('keterangan', `${keterangan}`)
                    $.ajax({
                        url: "{{ route('booking.update_alasan') }}",
                        type: 'post',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            panggilAntrian(id, nomor_antrian, namaLayanan, status, satker_id,
                                layanan_id, user_id)
                        }
                    });
                }
            })
        }

        function panggilAntrian(id, nomorAntrian, namaLayanan, status, satker_id, layanan_id, user_id) {
            Fdata = new FormData()
            Fdata.append('id', id)
            Fdata.append('status', status)
            Fdata.append('satker_id', satker_id)
            Fdata.append('layanan_id', layanan_id)
            Fdata.append('user_id', user_id)
            $.ajax({
                type: "POST",
                url: "{{ route('changeStatusAntrian') }}",
                data: Fdata,
                processData: false,
                contentType: false,
                success: function(result) {
                    swal.fire({
                        title: namaLayanan,
                        text: 'Antrian Nomor ' + nomorAntrian + '',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Proses',
                        cancelButtonText: 'Tidak Hadir',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.backdrop) return;
                        if (result.dismiss === Swal.DismissReason.cancel) status = 4

                        panggilProses(id, nomorAntrian, namaLayanan, status, satker_id, layanan_id,
                            user_id)
                    })
                }
            });
        }

        function batalUser(id, nomorAntrian, namaLayanan, status, satker_id, layanan_id, user_id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    cancelButton: 'btn btn-danger',
                    confirmButton: 'btn btn-success'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: namaLayanan,
                text: 'Apakah anda ingin membatalkan nomor antrian ' + nomorAntrian + '',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.backdrop || result.dismiss === Swal.DismissReason.cancel)
                    return;

                panggilProses(id, nomorAntrian, namaLayanan, status, satker_id, layanan_id, user_id)
            })
        }

        function panggilProses(id, nomorAntrian, namaLayanan, status, satker_id, layanan_id, user_id, user) {
            $('.btn' + nomorAntrian).attr('hidden', true)
            $('.prosess').html(`
            <button type="button" class="btn btn-success btn-load">
                <span class="d-flex align-items-center">
                    <span class="spinner-border flex-shrink-0" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                    <span class="flex-grow-1 ms-2">
                        Loading...
                    </span>
                </span>
            </button>
        `);
            Fdata = new FormData()
            Fdata.append('id', id)
            Fdata.append('status', status)
            Fdata.append('satker_id', satker_id)
            Fdata.append('layanan_id', layanan_id)
            Fdata.append('user_id', user_id)
            Fdata.append('user', user)
            $.ajax({
                type: "POST",
                url: "{{ route('changeStatusAntrian') }}",
                data: Fdata,
                processData: false,
                contentType: false,
                success: function(result) {
                    $('#dataTable').DataTable().ajax.reload();
                }
            });
        }

        setInterval(() => {
            $('#dataTable').DataTable().ajax.reload();
        }, 5000);

        function EditUlang(id, satker_id, satker, layanan_id, layanan, user, tanggal_hadir) {
            var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            var d = new Date({{ date('Y-m-d') }});
            var dayName = days[d.getDay()];

            var day = "{{ \Carbon\Carbon::now()->addDays(1)->format('Y-m-d') }}"
            if (dayName == "Friday")
                day = "{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}"

            $('#modal_content_layanan').html(`
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalgridLabel">Mengubah Tanggal Booking Layanan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-xxl-12">
                        <label class="form-label">Nama Satker</label>
                        <input type="text" class="form-control" id="esatker" value="` + satker + `" disabled />
                    </div>
                    <div class="col-xxl-12">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" class="form-control" id="elayanan" value="` + layanan + `" disabled />
                    </div>
                    <div class="col-xxl-12">
                        <label class="form-label">Nama User</label>
                        <input type="text" class="form-control" id="euser" value="` + user +
                `" disabled />
                    </div>
                    <div class="col-xxl-12">
                        <label class="form-label">Tanggal Hadir</label>
                        <input type="date" id="etanggal_hadir" class="form-control" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" max="` +
                day + `" value="{{ \Carbon\Carbon::parse(`+tanggal_hadir+`)->format('Y-m-d') }}">
                    </div>
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary btn-simpan-tanggal">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        `)
            $('.btn-simpan-tanggal').click(function() {
                fd = new FormData()
                fd.append('id', id)
                fd.append('tanggal_hadir', $('#etanggal_hadir').val())
                fd.append('satker_id', satker_id)
                fd.append('satker', satker)
                fd.append('layanan_id', layanan_id)
                fd.append('layanan', layanan)
                $.ajax({
                    type: "POST",
                    url: "{{ route('booking.ubah_tanggal') }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        $('#dataTable').DataTable().ajax.reload();
                        $('#exampleModal').modal('hide');
                    }
                });
            })
        }

        $('.btn-pdf').on('click', function() {
            $('#modal_content_layanan').html(`
            <div class="modal-body text-center p-5">
                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px"></lord-icon>

                <div class="mt-4">
                    <h4 class="mb-3">Sedang Mendownload PDF</h4>
                    <p class="text-muted mb-4"> Mohon Tunggu Sebentar. </p>
                </div>
            </div>
        `)
            $("#staticBackdrop").modal('show');

            var fd = new FormData()
            var satker_id = $('#satker_id').val() ?? $('#satker').val()
            fd.append('satker_id', satker_id)
            fd.append('layanan_id', $('#layanan_id').val())
            fd.append('tanggal_awal', $('#tanggal_awal').val())
            fd.append('tanggal_akhir', $('#tanggal_akhir').val())
            fd.append('status', $('#status').val())
            $.ajax({
                type: 'post',
                url: "{{ route('booking.pdf') }}",
                data: fd,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob'
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

                        setTimeout(function() {
                            URL.revokeObjectURL(downloadUrl);
                        }, 100); // cleanup
                    }
                }
            }).done(function() { //use this
                $('#exampleModal').modal('hide')
            });
        })
    </script>
@endsection
