@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Satker</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <form action="{{ route('satkers.store') }}" method="POST">
                        @csrf
                        <input type="checkbox" id="chkall" /> selectAll
                        <div class="col-md-12 mb-4">
                            <label for="provinsi_id" class="form-label">Provinsi</label>
                            <select id='provinsi_id' name="provinsi_id" multiple="multiple"></select>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="satker" class="form-label">Satker</label>
                            <input id="satker" name="satker" class="form-control"
                                placeholder="Masukan Nama Satker Baru" />
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="kode_satker" class="form-label">Kode</label>
                            <input id="kode_satker" name="kode_satker" class="form-control"
                                placeholder="Masukan Kode Satker" />
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="address" class="form-label">Alamat</label>
                            <input id="address" name="address" class="form-control" placeholder="Masukan Alamat" />
                        </div>
                        <button class="btn btn-success form-control"><i
                                class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Simpan</button>
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
        $(document).ready(function() {
            $('#provinsi_id').select2({
                ajax: {
                    url: "{{ route('api.provinsi') }}",
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
                    },
                    cache: true
                }
            });

            $('#chkall').on('click', function() {
                var $select2 = $('#provinsi_id');
                $select2.find('li').prop('selected', true);
                $select2.trigger('change');
            });
        });
    </script>
@endsection
