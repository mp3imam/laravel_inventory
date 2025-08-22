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
                            <a type="button" href="{{ route('upload_banner.create') }}" class="btn btn-success btn-label">
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
                                <div class="col-xxl-3 col-md-4 mb-3">
                                    <label>Filter Kategori</label>
                                    <select id='category_id' name="category_id" class="form-control"
                                        id="choices-single-no-search" name="choices-single-no-search" data-choices
                                        data-choices-search-false data-choices-removeItem>
                                        <option value=''>Pilih Semua</option>
                                        <option value='1'>{{ \App\Models\UploadBannerModel::KATEGORY_SMARTTV }}
                                        </option>
                                        <option value='2'>{{ \App\Models\UploadBannerModel::KATEGORY_KIOSK }}
                                        </option>
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
                                            <th>User</th>
                                            <th>Banner</th>
                                            <th>Kategori</th>
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
                    url: "{{ route('upload_banner.list') }}",
                    data: function(d) {
                        d.category_id = $('#category_id').val()
                    }
                },
                columns: [{
                    data: "id",
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'satkers',
                    name: 'user',
                }, {
                    data: 'banner',
                    name: 'Banner',
                    render: function(data) {
                        return `<img width="100px" height="100px" src="` +
                            data + `"></img>`
                    }
                }, {
                    data: 'kategori',
                    name: 'Kateogri',
                }, {
                    data: 'action',
                    name: 'Action',
                    sortable: false
                }]
            });

            $('#category_id').change(function() {
                table.draw();
            });
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
                        url: "{{ url('upload_banner') }}" + '/' + id,
                        data: {
                            "_method": 'delete',
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(result) {
                            $('#dataTable').DataTable().ajax.reload()
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'banner Telah Di hapus',
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
