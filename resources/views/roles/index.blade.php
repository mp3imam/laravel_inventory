@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

@include('components.breadcrumb')
@include('sweetalert::alert')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div>
                    <a type="button" href="{{ route('roles.create') }}"
                        class="btn btn-primary btn-label">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-shield-user-line label-icon align-middle fs-16 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                Tambah
                            </div>
                        </div>
                    </a>

                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th> Role Name</th>
                        <th width="750px">Hak Akses</th>
                        <th>Action</th>
                    </tr>
                    @foreach($roles as $key => $role)
                    @php
                        $idRole = Spatie\Permission\Models\Role::find($role->id);
                    @endphp
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($idRole->getPermissionNames() as $v)
                                <span class="badge text-bg-primary">{{ $v }} </span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-success">
                                    <span class="icon-on"><i class="ri-file-edit-line align-bottom me-1"></i> Ubah</span>
                                </a>
                                    {{-- {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy',
                                    $role->id],'style'=>'display:inline']) !!} --}}
                                    {{-- {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} --}}
                                    {{-- {{ Form::button('<span class="icon-on"><i class=" ri-delete-bin-5-line align-bottom me-1"></i>Hapus</span>', ['data-id' => $role->id,'class' => 'btn btn-danger btn-sm button', 'type' => 'submit']) }}
                                    {!! Form::close() !!} --}}
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
            {!! $roles->links('vendor.pagination.bootstrap-5') !!}
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).on('click', '.button', function (e) {
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
            .then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "post",
                        url: "{{ url('roles') }}" + '/' + id,
                        data: {
                            "_method": 'delete',
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (result) {
                            Swal.fire({
                                title: 'Hapus!',
                                text: 'Data Berhasil Dihapus.',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            }).then(function(){
                                    location.reload();
                                });
                        }
                    });
                }
            });
    });

</script>
@endsection
