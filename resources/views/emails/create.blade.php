@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')

<div class="row">
    <div class="col-xxl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Kustom Email</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form action="{{ route('mail.store') }}" method="Post" class="needs-validation" novalidate>
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label for="name" class="form-label">Hostaname</label>
                        <input type="text" name="hostname" class="form-control" placeholder="contoh: smtp.gmail.com">
                    </div>
                    <div class="mb-3">
                        <label for="To" class="form-label">Port</label>
                        <input type="text" name="port" class="form-control" id="To" placeholder="contoh: 465 atau 587">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @if ($errors->first('email', ':message')) is-invalid @endif" placeholder="contoh: fauza75@gmail.com" required>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $errors->first() }}
                        </div>
                        @enderror

                    </div>
                    <div class="mb-3">
                        <label for="To" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="To" placeholder="Password" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="bodyInput" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="body" id="bodyInput" rows="3"
                            placeholder="Deskripsi" required></textarea>
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
        },
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
            },
        });
    });

    $('#satker_id').change(function() {
        var satker_id = $(this).val()
        $("#satker").val($("#satker_id option:selected").text());
        $('#layanan_id').select2({
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ url('api/layanan_sakters') }}" + '?satker_id=' + satker_id,
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
            },
        });
    });

    $('#satker_id').change(function() {
        $("#satker").val($("#satker_id option:selected").text());
    });

    $('#layanan_id').change(function() {
        $("#layanan").val($("#layanan_id option:selected").text());
    });
</script>
@endsection
