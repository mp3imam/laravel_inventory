@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
@section('css')

<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link
    href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
    rel="stylesheet"
/>
@endsection
@include('components.breadcrumb')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Ubah Berita</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="col-xxl-6">
                    <form action="{{ route('berita.updateBerita') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @Method('POST')
                        <div class="row mb-3">
                            <div class="col-lg-3">
                                <label for="nameInput" class="form-label">Judul</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="hidden" class="form-control" name="id" value="{{ $data->id}}">
                                <input type="text" class="form-control" id="judul" name="judul" id="nameInput"
                                    placeholder="{{ $data->judul }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3">
                                <label for="meassageInput" class="form-label">Berita</label>
                            </div>
                            <div class="col-lg-9">
                                <textarea class="form-control" id="meassageInput" name="berita" rows="3" placeholder="{{ $data->berita }}"></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-3">
                                <label for="meassageInput" class="form-label">Kategori</label>
                            </div>
                            <div class="col-lg-9">
                                <select name="category" class="form-control" data-choices>
                                <option value="">-- Pilih --</option>
                                @foreach ( $category as $cat )
                                        <option value="{{ $cat->id }}" {{ $data->category_id == $cat->id ? 'selected' : '' }}> {{ $cat->name }}</option>
                                @endforeach
                                </select>

                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3">
                                <label for="nameInput" class="form-label">Gambar</label>
                            </div>
                            <div class="col-lg-9">
                                <input type="file" name="attachment" class="my-pond" id="attachment">

                            </div>
                        </div>

                        <div class="text-left">
                            <div class="col-12">
                                <a href="{{ route('berita.index') }}" class="btn btn-border btn-soft-success">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="bx bx-arrow-back label-icon align-middle fs-16 me-2"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            Kembali
                                        </div>
                                    </div>
                                </a>
                                <button type="submit" class="btn btn-primary ">

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
                        </div>
                    </form>
                </div>
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end col -->
</div>
<!--end row-->

@endsection
@section('script')
<!-- include FilePond plugins -->
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>

<!-- include FilePond jQuery adapter -->
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

<script>
    FilePond.registerPlugin(FilePondPluginImagePreview);

    const inputElement = document.querySelector('input[id="attachment"]');
    const pond = FilePond.create(inputElement);
    const pondBox = document.querySelector('.filepond--root');
    pondBox.addEventListener('FilePond:addfile', e => {
            console.log('file added event', e.detail);
            var fileName = pond.getFile().filename;
            console.log(fileName);
        });

    FilePond.setOptions({
        server: {
            process: "/update",

            headers:{
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    });
</script>
    <script>

 $(function(){

  // First register any plugins
  $.fn.filepond.registerPlugin(FilePondPluginImagePreview);

  // Turn input element into a pond
  $('.my-pond').filepond();

  // Set allowMultiple property to true
//   $('.my-pond').filepond('allowMultiple', true);

  // Listen for addfile event
  $('.my-pond').on('FilePond:addfile', function(e) {
    //   console.log('file added event', e);
  });

  // Manually add a file using the addfile method
  var img = '{{ $data->gambar }}';
  $('.my-pond').first().filepond('addFile', img).then(function(file){
    console.log('file added', file);
  });

});

    </script>
@endsection
