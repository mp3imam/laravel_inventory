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
                        @php $user = Auth::user()->roles[0]->name @endphp
                        @if ($user == 'super-admin')
                            <div class="col-sm-auto mb-3">
                                <a type="button" href="{{ route('dokumentasi.create') }}"
                                    class="btn btn-success btn-label">
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
                            <div class="row g-4">
                                <div class="row mt-4">
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label>Filter Role</label>
                                        <select id='role_id' name="role_id">
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
                                            <th>Judul</th>
                                            @if ($user == 'super-admin')
                                                <th>Role</th>
                                            @endif
                                            <th width="150px">Tanggal Dibuat</th>
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
                url: "{{ route('dokumentasi.list') }}",
                data: function(d) {
                    d.role_id = $('#role_id').val()
                }
            },
            columns: [{
                data: "id",
                sortable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                data: 'judul',
                name: 'Judul',
            }, {
                <?php if ($user == 'super-admin') { ?>
                data: 'user',
                name: 'Role'
            }, {
                <?php } ?>
                data: 'created_at',
                name: 'Tanggal',
            }, {
                data: 'action',
                name: 'Action',
                sortable: false
            }]
        });

        $('#role_id').change(function() {
            table.draw();
        });
    });

    $('#role_id').select2({
        ajax: {
            url: "{{ route('api.role_users_not_guest') }}",
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
                    url: "{{ url('dokumentasi') }}" + '/' + id,
                    data: {
                        "_method": 'delete',
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        $('#dataTable').DataTable().ajax.reload()
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'File Telah Di hapus',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        })
                    }
                });
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
