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
                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-xl-0" role="alert">
                                <span>{{ session()->get('error') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="col-sm-auto mb-3">
                            <a type="button" href="{{ route('upload_video.create') }}" class="btn btn-success btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <input id="role" value="{{ Auth::user()->roles[0]->name }}" hidden></i>
                                        <i class="bx bx-user-plus label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Tambah
                                    </div>
                                </div>
                            </a>
                        </div>
                        @php $user = Auth::user()->roles[0]->name @endphp
                        @if ($user == 'super-admin')
                            <div class="row g-4">
                                <div class="row mt-4">
                                    <div class="col-xxl-3 col-md-4 mb-3">
                                        <label>Filter Provinsi</label>
                                        <select id='provinsi_id' name="provinsi_id[]" multiple="multiple"></select>
                                    </div>
                                    <div class="col-xxl-3 col-md-4 mb-3">
                                        <label>Filter Satker</label>
                                        <select id='satker_id' name="satker_id[]" multiple="multiple"></select>
                                    </div>
                                    <div class="col-xxl-3 col-md-4 mb-3">
                                        <label>Filter Kategori</label>
                                        <select id='category_id' name="category_id" class="form-control"
                                            id="choices-single-no-search" name="choices-single-no-search" data-choices
                                            data-choices-search-false data-choices-removeItem>
                                            <option value=''>Pilih Semua</option>
                                            <option value='1'>{{ \App\Models\UploadVideoModel::KATEGORY_SMARTTV }}
                                            </option>
                                            <option value='2'>{{ \App\Models\UploadVideoModel::KATEGORY_KIOSK }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xxl-3 col-md-4 mb-3">
                                        <label>Filter Role</label>
                                        <select id='role_id' name="role_id">
                                            <option value=''>Pilih Semua</option>
                                        </select>
                                    </div>
                                    <div class="col-xxl-3 col-md-4 mb-3">
                                        <label>Filter Status</label>
                                        <select id='status' name="status" class="form-control"
                                            id="choices-single-no-search" name="choices-single-no-search" data-choices
                                            data-choices-search-false data-choices-removeItem>
                                            <option value=''>Pilih Semua</option>
                                            <option value='0'>{{ \App\Models\UploadVideoModel::STATUS_TIDAK_AKTIF }}
                                            </option>
                                            <option value='1'>{{ \App\Models\UploadVideoModel::STATUS_AKTIF }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endauth
                        <div class="card">
                            <div class="card-body">
                                <table id="dataTable" class="table table-striped table-bordered table-sm "
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>Provinsi</th>
                                            <th>Satker</th>
                                            <th>Kategori</th>
                                            @if ($user == 'super-admin')
                                                <th>Role</th>
                                            @endif
                                            <th>Video</th>
                                            <th>Status</th>
                                            <th width="150px">Action</th>
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
                url: "{{ route('upload_video.list') }}",
                data: function(d) {
                    d.provinsi_id = $('#provinsi_id').val()
                    d.satker_id = $('#satker_id').val()
                    d.category_id = $('#category_id').val()
                    d.role_id = $('#role_id').val()
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
                data: 'provinsis',
                name: 'Provinsi',
            }, {
                data: 'satkers',
                name: 'Satker'
            }, {
                data: 'kategori',
                name: 'Kategori'
            }, {
                <?php if ($user == 'super-admin') { ?>
                data: 'role',
                name: 'Role'
            }, {
                <?php } ?>
                data: 'video',
                name: 'Video',
                render: function(data) {
                    return `<video width="100px" height="100px" controls> <source src="` +
                        data + `" type="video/mp4"></video>`
                }
            }, {
                data: 'status',
                name: 'Status'
            }, {
                data: 'action',
                name: 'Action',
                sortable: false
            }]
        });

        $('#provinsi_id').change(function() {
            table.draw();
        });

        $('#satker_id').change(function() {
            table.draw();
        });

        $('#category_id').change(function() {
            table.draw();
        });

        $('#role_id').change(function() {
            table.draw();
        });

        $('#status').change(function() {
            table.draw();
        });
    });

    $('#provinsi_id').select2({
        ajax: {
            url: "{{ route('api.provinsi') }}",
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

    $('#satker_id').select2({});

    $('#provinsi_id').change(function() {
        changeProvinsi()
    });

    changeProvinsi()

    function changeProvinsi() {
        var provinsi_id = $('#provinsi_id').val()
        $('#satker_id').select2({
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('api.satker') }}?provinsi_id=" + provinsi_id,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                id: item.id,
                                text: item.name
                            }
                        })
                    };
                }
            }
        });
    }

    $('#role_id').select2({
        ajax: {
            url: "{{ route('api.role_admin_superadmin') }}",
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: $.map(data.data, function(item) {
                        return {
                            text: item.name,
                            id: item.name
                        }
                    })
                };
            },
            cache: true
        }
    });

    function alert_delete(id) {
        Swal.fire({
            title: `Hapus Data Upload`,
            text: "Apakah anda yakin untuk menghapus data Upload",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Iya!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ url('upload_video') }}" + '/' + id,
                    data: {
                        "_method": 'delete',
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        $('#dataTable').DataTable().ajax.reload()
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Video Telah Di hapus',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        })
                    }
                });
            }
        });
    }

    function video_status(id, status, satker_id) {
        var fd = new FormData()
        fd.append('id', id)
        fd.append('status', status)
        fd.append('satker_id', satker_id)
        $.ajax({
            type: "post",
            url: "{{ route('upload_video.video_status') }}",
            data: fd,
            processData: false,
            contentType: false,
            success: function(result) {
                if (result.data == true) {
                    $('#dataTable').DataTable().ajax.reload()
                } else {
                    Swal.fire({
                        title: 'Status!',
                        text: 'Status aktif maksimal hanya ' + result.max +
                            ', silahkan merubah ke tidak aktif agar status ini bisa diaktifkan',
                        icon: 'warning',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false
                    })
                }
            }
        });
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endsection
