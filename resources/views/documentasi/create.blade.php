@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-4">
                    <form action="{{ route('dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 mb-4">
                            <label for="role" class="form-label">Role</label>
                            <select id='role_id' name="role_id">
                                <option value=''>Pilih Role</option>
                            </select>
                            <div><span id="file_error" class="text-danger"></span></div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="judul" class="form-label">Judul Dokumentasi</label>
                            <input id="judul" name="judul" class="form-control" />
                            <div><span id="file_error" class="text-danger"></span></div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="video" class="form-label">Upload File Dokumentasi Format : PDF</label>
                            <input type="file" id="file" name="file" class="form-control demoInputBox"
                                accept="application/pdf" />
                            <div><span id="file_error" class="text-danger"></span></div>
                        </div>
                        <button type="submit" class="btn btn-success form-control"><i
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
        $('#role_id').select2({
            ajax: {
                url: "{{ route('api.role_users') }}",
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
    </script>
@endsection
