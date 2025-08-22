@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('questions.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label for="provinsi_id" class="form-label">Provinsi</label>
                            <select id='provinsi_id' name="provinsi_id" required></select>
                            @error('provinsi_id') <div class="text-danger">Pilih Data Provinsi</div> @enderror
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="satker_id" class="form-label">Satker</label>
                            <select id='satker_id' name="satker_id" required></select>
                            @error('satker_id') <div class="text-danger">Pilih Data Satker</div> @enderror
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="layanan_id" class="form-label">Layanan</label>
                            <select id='layanan_id' name="layanan_id" required></select>
                            @error('layanan_id') <div class="text-danger">Pilih Data Layanan</div> @enderror
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="question" class="form-label">Pertanyaan</label>
                            <textarea id="question" name="question" class="form-control" placeholder="Masukan Pertanyaannya" required></textarea>
                            @error('question') <div class="text-danger">Masukan Pertanyaan</div> @enderror
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="deskripsi" class="form-label">Tahun Periode</label>
                            <select id='periode' name="periode" class="form-select form-control" required>
                                <option value="">-- Pilih Semua --</option>
                                <option value="{{ \Carbon\Carbon::now()->format('Y') }}">{{ \Carbon\Carbon::now()->format('Y') }}</option>
                                <option value="{{ \Carbon\Carbon::now()->addYears(1)->format('Y') }}">{{ \Carbon\Carbon::now()->addYears(1)->format('Y') }}</option>
                                <option value="{{ \Carbon\Carbon::now()->addYears(2)->format('Y') }}">{{ \Carbon\Carbon::now()->addYears(2)->format('Y') }}</option>
                            </select>
                        @error('periode') <div class="text-danger">Masukan Periode</div> @enderror
                        </div>
                    </div>
                    <button class="btn btn-success form-control"><i class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Save</button>
                </form>            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
@section('script')

<script>
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
    $('#layanan_id').select2();

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
            }
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
