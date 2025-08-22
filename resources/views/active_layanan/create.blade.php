@extends('layouts.master')

<!-- @section('title') {{ $title }} @endsection -->

@section('content')

@include('components.breadcrumb')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Add Aktifasi Layanan</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <form id="myForm" action="{{ route('active_layanans.store') }}" method="POST"
                    class="row g-3 needs-validation" novalidate>
                    @csrf
                    @Method('POST')
                        <div class="col-md-8">
                            <label for="users" class="form-label">Pilih Users</label>
                            <select name="users_id" id="users_id" class="form-select" >
                                <option value="0">Pilih User</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="validationCustom01" class="form-label">Provinsi</label>
                            <select name="provinsi_id" id="provinsi_id" class="form-select" required>
                                <option value="0">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="validationCustom02" class="form-label">Pilih Satker</label>
                            <select name="satker_id" id="satker_id" class="form-select" required>
                                <option value="0">Pilih Satker</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button id="btnFetch" class="btn btn-success btn-border btn-load">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="bx bxs-save label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Simpan
                                    </div>
                                </div>
                            </button>
                        </div>
                </form>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
@section('script')
<script type="text/javascript">
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
</script>
@endsection
