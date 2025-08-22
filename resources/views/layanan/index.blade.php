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
                    <div id="customerList">
                        <div class="col-sm-auto mb-3">
                            <a type="button" href="{{ route('layanans.create') }}" class="btn btn-success btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-user-plus label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Tambah
                                    </div>
                                </div>
                            </a>
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
                        <div class="row g-4">
                            <div class="row mt-4">
                                <div class="col-xxl-3 col-md-6 mb-3">
                                    <label>Filter Layanan</label>
                                    <select id='layanan_id' name="layanan_id[]" multiple="multiple">
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
                                            <th width="10px">No</th>
                                            <th width="30px">Layanan</th>
                                            <th width="30px">Kode</th>
                                            <th width="30px">Warna</th>
                                            <th width="30px">Deskripsi</th>
                                            <th width="50px">Icon</th>
                                            <th width="30px">Action</th>
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
                    url: "{{ route('layanans.index') }}",
                    data: function(d) {
                        d.layanan_id = $('#layanan_id').val()
                    }
                },
                columns: [{
                    data: "id",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'name',
                    name: 'Layanan'
                }, {
                    data: 'kode',
                    name: 'Kode'
                }, {
                    data: 'warnas',
                    name: 'Warna'
                }, {
                    data: 'deskripsi',
                    name: 'Deskripsi'
                }, {
                    data: 'icons',
                    name: 'icon',
                    sortable: false,
                    render: function(data, type, full, meta) {
                        if (data == "-") return "";
                        return "<img src=\"" + data + "\" height=\"50\" widht=\"50\" />";
                    },
                }, {
                    data: 'action',
                    name: 'Action',
                    sortable: false,
                }]
            });

            $('#layanan_id').change(function() {
                table.draw();
            });
        });

        $('#layanan_id').select2({
            ajax: {
                url: "{{ route('api.layanan') }}",
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

        function alert_delete(id, nama) {
            Swal.fire({
                title: `Hapus Data Layanan`,
                text: "Apakah anda yakin untuk menghapus data Layanan '" + nama + "'",
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
                        success: function(result) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Your file has been deleted.',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }

            });
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
            $("#exampleModal").modal('show');

            var fd = new FormData()
            fd.append('layanan_id', $('#layanan_id').val())
            $.ajax({
                type: 'post',
                url: "{{ route('layanans.pdf') }}",
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
