@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('content')
    @include('components.breadcrumb')
    @include('sweetalert::alert')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="mb-3">
                        <a type="button" href="{{ route('users.create') }}" class="btn btn-primary btn-label">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bx bx-user-plus label-icon align-middle fs-16 me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Tambah User
                                </div>
                            </div>
                        </a>
                    </div>

                    @role('super-admin')
                        <form action="{{ route('users.index') }}" method="GET">
                            <div class="row">
                                <div class="col-xxl-3 col-md-4 mb-3">
                                    <input type="text" name="nama" class="form-control" id="placeholderInput"
                                        placeholder="Nama" value="{{ Request::input('nama') }}">
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <input type="text" name="email" class="form-control" id="placeholderInput"
                                        placeholder="Email" value="{{ Request::input('email') }}">
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <input type="text" name="satker" class="form-control" id="placeholderInput"
                                        placeholder="Satker" value="{{ Request::input('satker') }}">
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <button class="btn btn-primary"><i class="ri-search-line align-bottom me-1"></i>
                                        Cari</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary"><i
                                            class="ri-arrow-go-back-line align-bottom me-1"></i> Kembali</a>
                                </div>
                            </div>

                        </form>
                    @endrole
                </div><!-- end card header -->


                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <tr class="table-light">
                            <th class="text-center">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Nip</th>
                            <th>Satker</th>
                            <th>Provinsi</th>
                            <th>Roles</th>
                            <th></th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($data as $key => $user)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->nip }}</td>
                                <td>{{ $user->satker->name ?? '' }}</td>
                                <td>{{ $user->provinsi->name ?? '' }}</td>
                                <td>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            <span class="badge text-bg-success">{{ $v }}</span>
                                        @endforeach
                                    @endif
                                </td>

                                <td>
                                    @role('super-admin')
                                        @if ($user->active === 1)
                                            <span class="badge badge-soft-success text-uppercase">Aktif</span>
                                        @else
                                            <a href="#" class="aktif" data-id="{{ $user->id }}">
                                                <span class="badge badge-soft-danger text-uppercase">Block</span>
                                            </a>
                                        @endif
                                    @else
                                    @endrole

                                </td>

                                <td>
                                    <a class="btn btn-sm btn-success" href="{{ route('users.edit', $user->id) }}"><i
                                            class="ri-file-edit-line align-bottom me-1"></i> Ubah</a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                    @role('super-admin')
                                        {{ Form::button('<span class="icon-on"><i class=" ri-delete-bin-5-line align-bottom me-1"></i>Hapus</span>', ['data-id' => $user->id, 'class' => 'btn btn-danger btn-sm button', 'type' => 'submit']) }}
                                    @endrole
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

            </div>
            {!! $data->links('vendor.pagination.bootstrap-5') !!}

        </div>
    </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.button', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            Swal.fire({
                    title: "Anda Yakin?",
                    text: "Apakah Ingin Menghapus data ini!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal",
                    buttonsStyling: false,
                    showCloseButton: true
                })
                .then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "post",
                            url: "{{ url('users') }}" + '/' + id,
                            data: {
                                "_method": 'delete',
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                Swal.fire({
                                    title: 'Hapus!',
                                    text: 'Data Berhasil Dihapus.',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
        });
    </script>
    <script>
        $(document).on('click', '.aktif', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);
            Swal.fire({
                    title: "Anda Yakin?",
                    text: "Apakah Ingin Mengaktifkan Kembali Akun",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    cancelButtonText: "Batal",
                    confirmButtonText: "Ya",
                    buttonsStyling: false,
                    showCloseButton: true
                })
                .then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "post",
                            url: "{{ url('users/active') }}" + '/' + id,
                            data: {
                                "_method": 'post',
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                Swal.fire({
                                    title: 'Aktif!',
                                    text: 'Kamu Berhasil Mengaktifkan Kembali Akun Tersebut.',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
        });
    </script>
@endsection
