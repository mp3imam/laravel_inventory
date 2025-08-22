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
                        <div class="col-xxl-3 col-md-4 mb-3">
                            <label>Filter Email</label><br>
                            <input id='email' name="email" class="form-control"/>
                        </div>
                        <div class="col-xxl-3 col-md-4 mb-3">
                            <label>Filter Users</label>
                            <select id="users_id" name="users_id[]" multiple="multiple" class="form-control"></select>
                        </div>
                        <div class="col-xxl-3 col-md-4 mb-3">
                            <label>Filter Kewewangan</label>
                            <select id="role_id" name="role_id[]" multiple="multiple" class="form-control"></select>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="dataTable" class="table table-striped table-bordered table-sm " cellspacing="0"
                            width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Kewenangan</th>
                                        <th>Tanggal Aktifitas</th>
                                        <th>Aktifitas Terakhir </th>
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
                url: "{{ route('listUsers') }}",
                data: function (d) {
                    d.email    = $('#email').val(),
                    d.users_id = $('#users_id').val(),
                    d.role_id = $('#role_id').val()
                }
            },
            columns: [{
                    data: "id",
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },{

                    data: 'name',
                    name: 'Username'
                },{
                    data: 'email',
                    name: 'Email'
                },{
                    data: 'role_user',
                    name: 'Kewewangan',
                    sortable: false
                },{
                    data: 'activity_date',
                    name: 'Tanggal Aktifitas',
                    sortable: false
                },{
                    data: 'user_activity',
                    name: 'Log User',
                    sortable: false
                }
            ]
        });

        $('#email').change(function () {
            table.draw();
        });

        $('#users_id').change(function () {
            table.draw();
        });

        $('#role_id').change(function () {
            table.draw();
        });
    });

    $('#users_id').select2({
        ajax: {
          url: "{{ route('api.all_users') }}",
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

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        fd.append('email', $('#email').val())
        fd.append('users_id', $('#users_id').val())
        fd.append('role_id', $('#role_id').val())
        $.ajax({
            type:'post',
            url: "{{ route('export_users.pdf') }}",
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

                    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                }
            }
        }).done(function() { //use this
            $('#exampleModal').modal('hide')
        });
    })
</script>
@endsection
