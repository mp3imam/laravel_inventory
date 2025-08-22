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
                    <div>
                        <a type="button" href="{{ route('berita.create') }}" class="btn btn-primary btn-label">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="ri-newspaper-line label-icon align-middle fs-16 me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Tambah
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-xxl-4 col-md-6 mb-4">
                            <div>
                                <input type="text" name="judul" class="form-control searchJudul"
                                    placeholder="Cari Judul">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-md-6 mb-4">
                            <div>
                                <input type="text" name="berita" class="form-control berita"
                                    placeholder="Cari Berita">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">

                <div class="card-body">
                    <table id="dataTable" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Berita</th>
                                <th>Berita</th>
                                <th>Gambar</th>
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
@endsection

@section('script')
    <script>
        $(document).on('click', '.button', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            Swal.fire({
                    title: "Anda Yakin?",
                    text: "Ingin Menghapus Data ini!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal",
                    buttonsStyling: false,
                    showCloseButton: true
                })
                .then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "post",
                            url: "{{ url('berita/delete') }}" + '/' + id,
                            data: {
                                "_method": 'delete',
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                Swal.fire({
                                    title: 'Hapus!',
                                    text: 'Data berhasil Terhapus.',
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
        });
    </script>

    <script type="text/javascript">
        $(function() {

            let table = $('#dataTable').DataTable({
                sDom: 'lrtip',
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('berita.index') }}",
                    data: function(d) {
                        d.judul = $('.searchJudul').val(),
                        d.berita = $('.berita').val(),
                        d.search = $('input[type="search"]').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'berita',
                        name: 'berita'
                    },
                    {
                        data: 'gambar',
                        name: 'gambar'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {

                        render: function (data, type, row) {
                            return type === 'display' && data.length > 50 ? data.substr(0, 50) + '....' : data;
                        },
                        targets: [1, 2]
                    }
                ],
                // responsive: {
                //     details: {
                //         display: $.fn.dataTable.Responsive.display.modal( {
                //             header: function ( row ) {
                //                 var data = row.data();
                //                 console.log(data);
                //                 return 'Details for '+data[6];
                //             }
                //         } ),
                //         renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                //             tableClass: 'table'
                //         } )
                //     }
                // },
            });

            $(".searchJudul").keyup(function() {
                table.draw();
            });

            $(".berita").keyup(function() {
                table.draw();
            });

        });
    </script>
@endsection
