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
                    <div class="row mt-3">
                        <div class="col-xxl-3 col-md-2 mb-3">
                            <label>Filter Provinsi</label>
                            <select id='provinsi_id' name="provinsi_id[]" multiple="multiple">
                            </select>
                        </div>
                        <div class="col-xxl-3 col-md-6 mb-3">
                            <label>Filter Satker</label>
                            <select id='satker_id' name="satker_id[]" multiple="multiple">
                            </select>
                        </div>
                        <div class="col-xxl-3 col-md-2 mb-3">
                            <label>Filter Nama</label>
                            <select id='users_id' name="users_id[]" multiple="multiple">
                            </select>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="dataTable" class="table table-striped table-bordered table-sm " cellspacing="0"
                            width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Provinsi</th>
                                        <th>Satker</th>
                                        <th>User</th>
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
                url: "{{ route('listActiveLayanan') }}",
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
                    data: 'user',
                    name: 'User'
                },{
                    data: 'provinsis',
                    name: 'Provinsi'
                },{
                    data: 'satkers',
                    name: 'Satker'
                },{
                    data: 'role_user',
                    name: 'Role User'
                },{
                    data: 'action',
                    name: 'Action',
                    sortable: false
                }
            ]
        });

        $('#provinsi_id').change(function () {
            table.draw();
        });

        $('#satker_id').change(function () {
            table.draw();
        });

        $('#users_id').change(function () {
            table.draw();
        });

    });

    $('#users_id').select2({
        ajax: {
          url: "{{ route('api.users') }}",
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

    $('#provinsi_id').select2({
        ajax: {
          url: "{{ route('api.provinsi') }}",
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

    $('#satker_id').select2();

    $('#provinsi_id').change(function() {
        var provinsi_id = $(this).val()
        $("#provinsi").val($("#provinsi_id option:selected").text());
        $('#satker_id').select2({
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ url('api/satker') }}" + '?provinsi_id=' + provinsi_id,
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

    function data_layanan(id, name){
        $('#modal_content_layanan').html(`
            <div class="modal-header">
                <h5 class="modal-title">Ubah Data Layanan Pada Admin</h5><span class="text-right h5">`+name+`</span>
            </div>
            <div class="modal-body">
                <table id="dataModal" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Nama Layanan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        `)
        $('#dataModal').DataTable({
            dom: 'lrtip',
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('listLayanan') }}/?id="+id
            },
            columns: [{
                    data: 'name',
                    name: 'Nama Layanan',
                    orderable: false
                },{
                    data: 'action',
                    name: 'Action',
                    orderable: false
                }
            ]
        });
    }

    function save_layanan(satker_id, layanan_id){
        var status = $('input.checkbox_check'+satker_id+layanan_id).is(':checked') ? 1 : 0

        Fdata = new FormData()
        Fdata.append('satker_id', satker_id )
        Fdata.append('layanan_id', layanan_id )
        Fdata.append('status', status )
        Fdata.append('_token', "{{ csrf_token() }}" )

        $.ajax({
            type: "POST",
            url: "{{ url('active_layanans') }}",
            data: Fdata,
            processData: false,
            contentType: false,
            success: function (result) {
            }
        });
    }

</script>
@endsection
