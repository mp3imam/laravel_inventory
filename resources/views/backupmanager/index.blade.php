@extends('layouts.master')

@section('title') {{ $title }} @endsection
@section('css')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Backup dan Restore Data</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row g-4 mb-3">
                        <div class="col-sm-auto">
                            <div>
                            <form action="{{route('backupmanager_create')}}" method="post" id="frmNew">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary">
                                    <span><i class=" ri-file-cloud-line align-bottom me-1"></i> Create Backup</span>
                                </button>
                            </form>
                            </div>
                        </div>
                    </div>
                <form id="frm" action="{{route('backupmanager_restore_delete')}}" method="post">
                    {!! csrf_field() !!}

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th style="text-align: center;" width="1">#</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Size</th>
                                <th style="text-align: center;">Health</th>
                                <th style="text-align: center;">Type</th>
                                <th style="text-align: center;">Download</th>
                                <th style="text-align: center;" width="1">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($backups as $index => $backup)
                                <tr>
                                    <td style="text-align: center;">{{++$index}}</td>
                                    <td>{{$backup['name']}}</td>
                                    <td class="date">{{$backup['date']}}</td>
                                    <td>{{$backup['size']}}</td>
                                    <td style="text-align: center;">
                                        <?php
                                        $okSizeBytes = 1024;
                                        $isOk = $backup['size_raw'] >= $okSizeBytes;
                                        $text = $isOk ? 'Good' : 'Bad';
                                        $icon = $isOk ? 'success' : 'danger';

                                        echo "<span class='badge badge-label bg-$icon'><i class='mdi mdi-circle-medium'></i>$text</span>";
                                        ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge badge-label bg-{{$backup['type'] === 'Files' ? 'primary' : 'success'}}"><i class='mdi mdi-circle-medium'></i>{{$backup['type']}}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('backupmanager_download', [$backup['name']])  }}">
                                        <i class="mdi mdi-cloud-download"></i>
                                        </a>
                                    </td>
                                    <td style="text-align: center;">
                                        <!-- <div class="form-check form-check-outline form-check-success mb-3"> -->
                                            <!-- <input class="form-check-input" type="checkbox" id="formCheck15" checked=""> -->
                                            <input type="checkbox" name="backups[]" class="form-check-input form-check-outline form-check-success chkBackup" value="{{$backup['name']}}">
                                        <!-- </div> -->
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br><br>

                    @if (count($backups))
                        <input type="hidden" name="type" value="restore" id="type">

                        <div class="pull-right" style="margin-right: 15px;">
                            <button type="submit" id="btnSubmit" class="btn btn-success" disabled="disabled">
                                <span><i class="ri-recycle-fill align-bottom me-1"></i> Restore</span>
                            </button>
                            <button type="submit" id="btnDelete" class="btn btn-danger" disabled="disabled">
                                <span><i class="ri-delete-bin-5-line align-bottom me-1"></i> Delete</span>

                            </button>
                        </div>
                        <div class="clearfix"></div>
                    @endif

                </form>
                </div><!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end col -->
    </div>
    <!--end row-->

@endsection

@push('styles')
    <style>
        #overlay {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999999999;
        }

        #overlay .overlay-message {
            position: fixed;
            left: 50%;
            top: 57%;
            height: 100px;
            width: 250px;
            margin-left: -120px;
            margin-top: -50px;
            color: #fff;
            font-size: 20px;
            text-align: center;
            font-weight: bold;
        }

        .spinner {
            position: fixed;
            left: 50%;
            top: 40%;
            height: 80px;
            width: 80px;
            margin-left: -40px;
            margin-top: -40px;
            -webkit-animation: rotation .9s infinite linear;
            -moz-animation: rotation .9s infinite linear;
            -o-animation: rotation .9s infinite linear;
            animation: rotation .9s infinite linear;
            border: 6px solid rgba(255, 255, 255, .15);
            border-top-color: rgba(255, 255, 255, .8);
            border-radius: 100%;
        }

        @-webkit-keyframes rotation {
            from {
                -webkit-transform: rotate(0deg);
            }
            to {
                -webkit-transform: rotate(359deg);
            }
        }

        @-moz-keyframes rotation {
            from {
                -moz-transform: rotate(0deg);
            }
            to {
                -moz-transform: rotate(359deg);
            }
        }

        @-o-keyframes rotation {
            from {
                -o-transform: rotate(0deg);
            }
            to {
                -o-transform: rotate(359deg);
            }
        }

        @keyframes rotation {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(359deg);
            }
        }

        table.dataTable tr.group td {
            background-image: radial-gradient(#fff, #eee);
            border: none;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
@endpush

@push('scripts')

<script src="{{ URL::asset('/assets/js/pages/sweetalerts.init.js') }}"></script>

    <script>

        $('.table').DataTable({
            "order": [],
            "responsive": true,
            "pageLength": 10,
            "autoWidth": false,
            aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [-1]
                }
            ],
            rowGroup: {
                dataSrc: 2
            }
        });

        var $btnSubmit = $('#btnSubmit');
        var $btnDelete = $('#btnDelete');
        var $type = $('#type');
        var type = 'restore';

        $btnSubmit.on('click', function () {
            $type.val('restore');
            type = 'restore';
        });

        $btnDelete.on('click', function () {
            $type.val('delete');
            type = 'delete';
        });

        $(document).on('click', '.chkBackup', function () {
            var checkedCount = $('.chkBackup:checked').length;

            if (checkedCount > 0) {
                $btnSubmit.attr('disabled', false);
                $btnDelete.attr('disabled', false);
            }
            else {
                $btnSubmit.attr('disabled', true);
                $btnDelete.attr('disabled', true);
            }

            if (this.checked) {
                $(this).closest('tr').addClass('warning');
            }
            else {
                $(this).closest('tr').removeClass('warning');
            }
        });

        $('#frm').submit(function () {
            var $this = this;
            var checkedCount = $('.chkBackup:checked').length;
            var $btn = $('#btnSubmit');

            if (!checkedCount) {
                swal("Please select backup(s) first!");
                return false;
            }

            if (checkedCount > 2 && type === 'restore') {
                swal("Please select one or two backups max.");
                return false;
            }

            if (type === 'restore') {
                var msg = 'Continue with restoration process ?';
                Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
                showCloseButton: true
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            title: 'Restored!',
                            text: 'Your file has been restored.',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(function (result){
                            if (result.value) {
                                $btn.attr('disabled', true);
                                $this.submit();
                            }
                        });

                    }

                });
            }
            if (type === 'delete') {
                msg = 'You want to delete selected backups ?';
                Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
                showCloseButton: true
                }).then(function (result) {
                    if (result.value) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Your file has been deleted.',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(function (result){
                            if (result.value) {
                                $btn.attr('disabled', true);
                                $this.submit();
                            }
                        });

                    }

                });
            }



            return false;
        });

        $('#frmNew').submit(function () {
            this.submit();
        });


    </script>
@endpush
