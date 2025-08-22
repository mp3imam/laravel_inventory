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
                    <div>
                        @if (auth()->user()->getRoleNames()->first() === 'super-admin')
                            <a type="button" href="{{ route('mail.create') }}" class="btn btn-primary btn-label">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-mail-settings-line label-icon align-middle fs-16 me-2"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        Kustom Mail
                                    </div>
                                </div>
                            </a>
                        @else
                        @endif

                    </div>
                </div><!-- end card header -->
            </div>
        </div><!-- end card header -->

        <div class="table-responsive">
            <table class="table align-middle table-nowrap mb-0">
                <tr class="table-light">
                    <th class="text-center">No</th>
                    <th>Host</th>
                    <th>Port</th>
                    <th>Username</th>
                    <th>Provinsi</th>
                    <th>Satker</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                @foreach ($data as $key => $mail)
                    <tr>
                        <td class="text-center">{{ ++$i }}</td>
                        <td>{{ $mail->hostname }}</td>
                        <td>{{ $mail->port }}</td>
                        <td>{{ $mail->email }}</td>
                        <td>{{ $mail->provinsi->name ?? '' }}</td>
                        <td>{{ $mail->satker->name ?? '' }}</td>
                        <td>{{ $mail->layan->name ?? '' }}</td>
                        @if ($mail->status === 0)
                            <td><span class="badge badge-soft-info badge-border">Menunggu</span></td>
                        @elseif ($mail->status === 1)
                            <td><span class="badge badge-soft-success badge-border">Diterima</span></td>
                        @else
                            <td><span class="badge badge-soft-danger badge-border">Ditolak</span></td>
                        @endif
                        <td>
                            @if ($mail->status === 2)
                                <button class="btn btn-sm btn-success button" data-id="{{ $mail->id }}"><i
                                        class="ri-mail-check-line align-bottom me-1"></i> Terima</button>
                            @else
                            @endif
                            {{-- <a class="btn btn-sm btn-success" href="{{ route('test.mail', $mail->id) }}"><i
                                        class="ri-mail-check-line align-bottom me-1"></i> test</a> --}}
                            <a class="btn btn-sm btn-success" href="{{ route('mail.edit', $mail->id) }}"><i
                                    class="ri-file-edit-line align-bottom me-1"></i> Ubah</a>
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
            Swal.fire({
                    title: "Apakah Yakin?",
                    text: "Apakah Kamu Yakin Ingin Mengubah Permintaan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    confirmButtonText: "Ya",
                    buttonsStyling: false,
                    showCloseButton: true
                })
                .then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "post",
                            url: "{{ url('mail/accepted') }}" + '/' + id,
                            data: {
                                "_method": 'post',
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                Swal.fire({
                                    title: 'Accepted!',
                                    text: 'Permintaan Diterima.',
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
