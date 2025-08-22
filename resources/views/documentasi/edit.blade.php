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
                    <form action="{{ route('dokumentasi.update', $detail->id) }}" method="POST" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="col-md-12 mb-4">
                            <label for="role" class="form-label">Role</label>
                            <select id='role_id' name="role_id">
                            </select>
                            <div><span id="file_error" class="text-danger"></span></div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="judul" class="form-label">Judul</label>
                            <input class="form-control" id="judul" name="judul" value="{{ $detail->judul }}" />
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="video" class="form-label">Upload File Dokumentasi Format : PDF</label>
                            @if ($detail->file)
                                <div>
                                    <i class="bx bx- bxs-file-pdf bx-md"></i>
                                    <a target='_blank' href='{{ $detail->file }}'
                                        class='btn btn-link btn-xl pb-4'>{{ $detail->judul }}</a>
                                </div>
                            @endif

                            <input type="file" id="file" name="file" class="form-control demoInputBox"
                                accept="application/pdf" />
                            <div><span id="file_error" class="text-danger"></span></div>
                        </div>
                        <button type="submit" class="btn btn-warning form-control"><i
                                class="bx bxs-save label-icon align-middle fs-16 me-2"></i> Update</button>
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
        $(function() {
            var dataRole = {
                id: "{{ $detail->user_id }}",
                text: "{{ $detail->user->roles[0]->name }}",
                selected: true
            };
            var newOptionRole = new Option(dataRole.text, dataRole.id, false, false);
            $('#role_id').append(newOptionRole).trigger('change');
            $('#role_id').select2();

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
        });
    </script>
@endsection
