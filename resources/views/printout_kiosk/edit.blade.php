@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ url('printout_kiosk', $detail->id) }}">
                        @csrf
                        @method('put')
                        <div class="col-md-12 mb-3">
                            <input class="form-control" id="id" name="id" value="{{ $detail->id }}" hidden />
                            <label for="bawah" class="form-label">Edit Cetak Print KiosK</label>
                            <input class="form-control" id="bawah" name="bawah" value="{{ $detail->bawah }}"
                                required />
                            @error('bawah')
                                <div class="text-danger">Masukan Edit Cetak Print KiosK</div>
                            @enderror
                        </div>
                        <button class="btn btn-warning form-control">Ubah</button>
                    </form>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
    <!--end row-->
@endsection
