@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')
@include('sweetalert::alert')

@include('components.breadcrumb')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div>
                    <a type="button" href="{{ route('notif.admin') }}"
                        class="btn btn-primary btn-label">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-shield-user-line label-icon align-middle fs-16 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                Admin
                            </div>
                        </div>
                    </a>
                    <a type="button" href="{{ route('notif.user') }}"
                        class="btn btn-info btn-label">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-file-user-line label-icon align-middle fs-16 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                User
                            </div>
                        </div>
                    </a>

                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th width="750px">Catatan</th>
                    </tr>
                    @foreach($notifications as  $n)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $n->data['name'] }}</td>
                            <td>{{ $n->data['email'] }}</td>
                            <td>{{ $n->data['noted'] ?? '' }}</td>

                        </tr>
                    @endforeach
                </table>

            </div>
            {!! $notifications->links('vendor.pagination.bootstrap-5') !!}
        </div>
    </div>
</div>

@endsection

@section('script')
@endsection
