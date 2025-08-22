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
                            <a type="button" href="{{ route('printout_kiosk.create') }}" class="btn btn-success btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
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
                                <div class="col-xxl-4 col-md-4 mb-3">
                                    <label>Filter Ucapan</label>
                                    <input id='bawah' name="bawah" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table id="dataTable" class="table table-striped table-bordered table-sm " cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th width="80%">Ucapan Printer KiosK</th>
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
        $(function() {
            var table = $('#dataTable').DataTable({
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('printout_kiosk.list') }}",
                    data: function(d) {
                        d.bawah = $('#bawah').val()
                    }
                },
                columns: [{
                    data: 'bawah',
                    name: 'Ucapan'
                }, {
                    data: 'action',
                    name: 'Action',
                    sortable: false
                }]
            });

            $('#bawah').change(function() {
                table.draw();
            });
        });

        function alert_delete(id, nama) {
            Swal.fire({
                title: `Hapus Data bawah`,
                text: "Apakah anda yakin untuk menghapus data bawah '" + nama + "'",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: "{{ url('printout_kiosk') }}" + '/' + id,
                        data: {
                            "id": id,
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
                                $('#dataTable').DataTable().ajax.reload();
                            });
                        }
                    });
                }

            });
        }
    </script>
@endsection
