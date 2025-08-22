@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Edit Kustom Email</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form action="{{ route('mail.update', $data->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="name" class="form-label">Username Mail</label>
                        <input type="email" name="username" class="form-control" id="name" value="{{ $data->username}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="To" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="To" value="{{ $data->password}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Host</label>
                        <input type="text" name="host" class="form-control" id="name" value="{{ $data->host}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="To" class="form-label">Port</label>
                        <input type="text" name="port" class="form-control" id="To" value="{{ $data->port}}" min="3" required>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ $data->title}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="bodyInput" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="body" id="bodyInput" rows="3" required>{{ $data->body }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Provinsi :</label>
                        <select id='provinsi_id' name="provinsi_id">
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Satker :</label>
                        <select id='satker_id' name="satker_id">
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="layanan_id">Pilih Layanan :</label>
                        <select id="layanan_id" name="layanan_id" class="form-select form-control">
                        </select>
                    </div>
                    <div>
                    <a href="{{ route('mail.index') }}" class="btn btn-light">
                        <span><i class="ri-arrow-go-back-line align-bottom me-1"></i> Kembali</span>
                    </a>
                    <button type="submit" class="btn btn-success">
                        <span><i class="ri-save-3-line align-bottom me-1"></i> Simpan</span>
                    </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
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

        var dataProvinsi = {id: "{{ $data->provinsi_id !== $data->provinsi_id ? "" : "" }}",text: "{{ $data->provinsi ? $data->provinsi->name : "" }}", selected: true};
        var newOptionProvinsi = new Option(dataProvinsi.text, dataProvinsi.id, false, false);
        $('#provinsi_id').append(newOptionProvinsi).trigger('change');

        var dataSatker = {id: "{{ $data->satker_id !== "" ? $data->satker_id : "" }}",text: "{{ $data->satker ? $data->satker->name : "" }}", selected: true};
        var newOptionSatker = new Option(dataSatker.text, dataSatker.id, false, false);
        $('#satker_id').append(newOptionSatker).trigger('change');

        $('#satker_id').select2()

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
    });
</script>
@endsection
