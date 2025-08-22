@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-lg-12">
        <a type="button" href="{{ route('inventory.create') }}" class="btn btn-success btn-label">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="bx bx-user-plus label-icon align-middle fs-16 me-2"></i>
                </div>
                <div class="flex-grow-1">
                    Tambah
                </div>
            </div>
        </a>
        <div class="card">
            <div class="card-body">
                <div id="customerList">
                    <div class="row mt-3">
                        <div class="col-xxl-3 col-md-2 mb-3">
                            <label>Filter Kategori</label>
                            <select id='categories_id' name="categories_id[]" multiple="multiple">
                            </select>
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label>Filter Nama Barang</label>
                            <input id='nama_barang' name="nama_barang" class="form-control"/>
                        </div>
                        <div class="col-xxl-3 col-md-2 mb-3">
                            <label>Filter Stock</label>
                            <input id='nama_barang' type="number" name="nama_barang" class="form-control"/>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="dataTable" class="table table-striped table-bordered table-sm " cellspacing="0"
                            width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah Stoc</th>
                                        <th>Tanggal Pembaruan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" id="modal_content_layanan">
                        </div>
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
                url: "{{ route('listStock') }}",
                data: function (d) {
                        d.provinsi_id = $('#provinsi_id').val(),
                        d.satker_id = $('#satker_id').val(),
                        d.users_id = $('#users_id').val()
                }
            },
            columns: [{
                    data: "id",
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },{
                    data: 'item_name',
                    name: 'Nama Barang'
                },{
                    data: 'stock',
                    name: 'Jumlah Stock'
                },{
                    data: 'tanggal_pembaruan',
                    name: 'Tanggal Pembaruan'
                },{
                    data: 'action',
                    name: 'Action',
                    sortable: false
                }
            ]
        });

        $('#categories_id').change(function () {
            table.draw();
        });

        $('#nama_barang').change(function () {
            table.draw();
        });

        $('#nama_barang').change(function () {
            table.draw();
        });

    });

    $('#categories_id').select2({
        placeholder: "Pilih Kategori Barang",
        allowClear: true,
        minimumResultsForSearch: Infinity,
        data: [
            { id: 1, text: "Barang Dasar" },
            { id: 2, text: "Barang Setengah Jadi" },
            { id: 3, text: "Barang Jadi" },
            { id: 4, text: "Barang Penolong" }
        ]
    });

    function stock(id, category, name, stock){
        // Perbaiki parameter dan id modal, serta pastikan input jumlah stok bisa diambil nilainya
        $('#modal_content_layanan').html(`
            <div class="modal-header">
                <h5 class="modal-title">Ubah Data Stok Barang</h5>
                <span class="text-right h5 ms-3">`+name+`</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" readonly disabled>
                        <option value="`+category+`">`+category+`</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" value="`+name+`"  readonly disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Aksi Stok</label>
                    <select class="form-select" id="aksi_stock">
                        <option value="1">Penambahan</option>
                        <option value="2">Pengurangan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock Awal</label>
                    <input type="number" class="form-control" value="`+stock+`" readonly disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" id="jumlah_stock" min="1" value="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="save_stock(`+id+`, $('#jumlah_stock').val())" class="btn btn-primary">Save changes</button>
            </div>
        `);

        // Pastikan modal benar-benar muncul
        var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
        modal.show();
    }

    function save_stock(id, stock){
        var aksi = $('#aksi_stock').val();

        Fdata = new FormData()
        Fdata.append('id', id )
        Fdata.append('stock', stock )
        Fdata.append('aksi', aksi )
        Fdata.append('_token', "{{ csrf_token() }}" )

        $.ajax({
            type: "POST",
            url: "{{ route('stock') }}",
            data: Fdata,
            processData: false,
            contentType: false,
            success: function (result) {
                $('#dataTable').DataTable().ajax.reload();

                if (result.status == 200) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Stock berhasil diperbarui.',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false,
                        timer: 1000
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger w-xs mt-2',
                    buttonsStyling: false,
                    timer: 1000
                });
            }
        });
    }


    function edit(id, category, name){
        // Perbaiki parameter dan id modal, serta pastikan input jumlah stok bisa diambil nilainya
        $('#modal_content_layanan').html(`
            <div class="modal-header">
                <h5 class="modal-title">Ubah Data Stok Barang</h5>
                <span class="text-right h5 ms-3">`+name+`</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="category" >
                        <option value="1" `+(category == '1' ? 'selected' : '')+`>Barang Dasar</option>
                        <option value="2" `+(category == '2' ? 'selected' : '')+`>Barang Setengah Jadi</option>
                        <option value="3" `+(category == '3' ? 'selected' : '')+`>Barang Jadi</option>
                        <option value="4" `+(category == '4' ? 'selected' : '')+`>Barang Penolong</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="name" value="`+name+`"  >
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="save_inventory(`+id+`)" class="btn btn-primary">Save changes</button>
            </div>
        `);

        // Pastikan modal benar-benar muncul
        var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
        modal.show();
    }


    function save_inventory(id, stock){
        var aksi = $('#aksi_stock').val();

        Fdata = new FormData()
        Fdata.append('id', id )
        Fdata.append('stock', stock )
        Fdata.append('aksi', aksi )
        Fdata.append('_token', "{{ csrf_token() }}" )

        $.ajax({
            type: "POST",
            url: "{{ url('inventory') }}/" + id,
            data: Fdata,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            contentType: false,
            success: function (result) {
                $('#dataTable').DataTable().ajax.reload();

                if (result.status === 200) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Stock berhasil diperbarui.',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false
                    }).then(function() {
                        $('#exampleModal').modal('hide');
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: result.message || 'Terjadi kesalahan.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger w-xs mt-2',
                        buttonsStyling: false
                    });
                }

            }
        });
    }

    function alert_delete(id, nama) {
        Swal.fire({
            title: `Hapus Data Inventori`,
            text: "Apakah anda yakin untuk menghapus data Inventori '" + nama + "'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ url('inventory') }}" + '/' + id,
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
                            $('#dataTable').DataTable().ajax.reload();
                        });
                    }
                });
            }
        });
    }


</script>
@endsection
