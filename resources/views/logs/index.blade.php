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
                <div class="card-header">
                    <h4 class="card-title mb-0">Log Aktifitas Pengguna</h4>
                </div>
                <div class="card-body">
                    <div id="customerList">
                        <div class="col-sm-auto mb-3">
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
                        <div class="row">
                            <div class="col-xxl-3 col-md-2 mb-3">
                                <label>Filter Subject</label>
                                <select id="menu" name="menu" class="form-control"></select>
                            </div>
                            <div class="col-xxl-3 col-md-2 mb-3">
                                <label>Filter Users</label>
                                <select id="user_id" name="user_id[]" multiple="multiple" class="form-control"></select>
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Tanggal Awal</label>
                                <input type="text" id="tanggal_awal" class="form-control flatpickr-input active"
                                    data-provider="flatpickr" data-date-format="d-m-Y"
                                    data-mindate="{{ \Carbon\Carbon::now()->subWeek(1)->format('d-m-Y') }}"
                                    data-maxdate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}"
                                    value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                            </div>
                            <div class="col-xxl-3 col-md-4 mb-3">
                                <label>Filter Tanggal Akhir</label>
                                <input type="text" id="tanggal_akhir" class="form-control flatpickr-input active"
                                    data-provider="flatpickr" data-date-format="d-m-Y"
                                    data-mindate="{{ \Carbon\Carbon::now()->format('d-m-Y') }}"
                                    data-maxdate="{{ \Carbon\Carbon::now()->addWeek(1)->format('d-m-Y') }}"
                                    value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" readonly="readonly">
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table id="dataTable" class="table table-striped table-bordered table-sm" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">Subject</th>
                                            <th width="20%">URL</th>
                                            <th width="5%">Method</th>
                                            <th width="10%">Browser</th>
                                            <th width="10%">User</th>
                                            <th width="10%">Tanggal Aktifitas Pengguna</th>
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
    <script type="text/javascript">
        $(function() {
            var table = $('#dataTable').DataTable({
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('logsList') }}",
                    data: function(d) {
                        d.menu = $('#menu').val(),
                            d.user_id = $('#user_id').val(),
                            d.tanggal_awal = $('#tanggal_awal').val(),
                            d.tanggal_akhir = $('#tanggal_akhir').val()
                    }
                },
                columns: [{
                    data: 'subject',
                    name: 'Subject'
                }, {
                    data: 'url',
                    name: 'URL',
                    render: function(data, type, row) {
                        return '<span class="d-inline-block text-truncate" style="max-width: 250px;">' +
                            data + "</span>";
                    }
                }, {
                    data: 'method',
                    name: 'Method'
                }, {
                    data: 'agent',
                    name: 'Agent',
                    render: function(data, type, row) {
                        return '<span class="d-inline-block text-truncate" style="max-width: 250px;">' +
                            data + "</span>";
                    }
                }, {
                    data: 'user',
                    name: 'User'
                }, {
                    data: 'created_at',
                    name: 'Tanggal'
                }]
            });

            $('#menu').change(function() {
                table.draw();
            });

            $('#user_id').change(function() {
                table.draw();
            });

            $('#tanggal_awal').change(function() {
                table.draw()
            });

            $('#tanggal_akhir').change(function() {
                table.draw()
            });
        });

        $('#user_id').select2({
            ajax: {
                url: "{{ route('api.all_users') }}",
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
                },
                cache: true
            }
        });

        $('#menu').select2({
            ajax: {
                url: "{{ route('api.get_menu') }}",
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
                },
                cache: true
            }
        });

        $('#user_id').select2({
            ajax: {
                url: "{{ route('api.all_users') }}",
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
                },
                cache: true
            }
        });

        $('#role_id').select2({
            ajax: {
                url: "{{ route('api.role_users') }}",
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
                },
                cache: true
            }
        });

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
            $("#exampleModal").modal('show');

            var fd = new FormData()
            fd.append('user_id', $('#user_id').val())
            fd.append('tanggal_awal', $('#tanggal_awal').val())
            fd.append('tanggal_akhir', $('#tanggal_akhir').val())
            fd.append('menu', $('#menu').val() ?? '')
            $.ajax({
                type: 'post',
                url: "{{ route('logs.pdf') }}",
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

                        setTimeout(function() {
                            URL.revokeObjectURL(downloadUrl);
                        }, 100); // cleanup
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
