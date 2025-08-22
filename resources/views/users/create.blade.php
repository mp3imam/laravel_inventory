@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
            </div><!-- end card header -->
            <div class="card-body">
            {!! Form::open(array('route' => 'users.store','method'=>'POST','class' => 'needs-validation','novalidate')) !!}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Name :</label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'required')) !!}
                        <div class="invalid-feedback">
                            Name Tidak Boleh Kosong.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email :</label>
                            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'required')) !!}
                            <div class="invalid-feedback">
                                Email Tidak Boleh Kosong.
                            </div>
                            @error('email')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NIP :</label>
                            {!! Form::number('nip', null, array('placeholder' => 'Nip','class' => 'form-control', 'required')) !!}
                            <div class="invalid-feedback">
                                Nip Tidak Boleh Kosong.
                            </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Role :</label>
                            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple', 'required', 'onchange'=> 'showDiv(this)')) !!}
                            <div class="invalid-feedback">
                                Role Tidak Boleh Kosong.
                            </div>
                    </div>
                    <div class="col-md-4 mb-3" id="hidden1" style="display:none;">
                        <label class="form-label">Provinsi :</label>
                        <select id='provinsi_id' name="provinsi_id">
                        </select>
                    </div>
                    <div class="col-md-4 mb-3" id="hidden2" style="display:none;">
                        <label class="form-label">Satker :</label>
                        <select id='satker_id' name="satker_id">
                        </select>
                    </div>


                    <div class="">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-go-back-line  align-bottom me-1"></i>Kembali
                            </a>
                        <button type="submit" class="btn btn-success">
                        <i class="ri-save-3-line align-bottom me-1"></i>Simpan
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}


            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    function showDiv(select){
       if(select.value== 'admin' || select.value== 'super-admin'){
        document.getElementById('hidden1').style.display = "block";
        document.getElementById('hidden2').style.display = "block";

       } else{
        document.getElementById('hidden1').style.display = "none";
        document.getElementById('hidden2').style.display = "none";
       }
    }
    </script>
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
