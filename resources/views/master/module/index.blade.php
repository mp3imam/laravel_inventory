@extends('layouts.master')

@section('title') {{ $title }} @endsection

@section('content')

    @include('components.breadcrumb')
    @include('sweetalert::alert')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">List Menu</h5>
                    <div>
                        {{-- <a class="btn btn-warning btn-label waves-effect waves-dark" href="{{ route('master.module.create') }}"><i class="ri-edit-2-line label-icon align-middle fs-16 me-2"></i> Edit</a> --}}
                    </div>
                </div>

                <div class="card-body">
                    <div class="col-md-12 dd" id="nestable-wrapper">
                        <ol class="dd-list dd-item dd-handle list-group col nested-list nested-sortable" style="cursor: all-scroll;">
                            @foreach($menu as $key => $val)
                                <li class="dd-item list-group-item nested-1" data-id="{{ $val->id }}" >
                                    <i class="{{ $val->module_icon }} fs-18 align-middle text-info me-2"></i>
                                    <span class="dd-handle align-middle">{{ $val->name }}</span>
                                    {{-- <div class="dd-option-handle float-end">
                                        <button type="button" class="btn btn-sm btn-warning modalConfirmEdit" data-bs-toggle="modal" data-id_edit="{{ $val->id }}">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger modalConfirmDelete" data-bs-toggle="modal" data-id_delete="{{ $val->id }}">Delete</button>
                                    </div> --}}

                                    @if(!empty($val->menu))
                                        <ol class="dd-list dd-item dd-handle list-group nested-list nested-sortable" style="cursor: all-scroll;">
                                            @foreach($val->menu as $keys => $sub_menu)
                                                <li class="dd-item list-group-item nested-2" data-id="{{ $sub_menu->id }}" >
                                                    <i class="{{ $sub_menu->module_icon }} fs-18 align-middle text-info me-2"></i>
                                                    <span class="dd-handle align-middle">{{ $sub_menu->name }}</span>
                                                    {{-- <div class="dd-option-handle float-end">
                                                        <button type="button" class="btn btn-sm btn-warning modalConfirmEdit" data-bs-toggle="modal" data-id_edit="{{ $sub_menu->id }}">Edit</button>
                                                        <button type="button" class="btn btn-sm btn-danger modalConfirmDelete" data-bs-toggle="modal" data-id_delete="{{ $sub_menu->id }}">Delete</button>
                                                    </div> --}}
                                                </li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="card-footer">
                    <form action="{{ route('module.reorder.post') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <textarea style="display: none;" name="nested_menu_array" id="nestable-output" class="col-md-6" rows="8"></textarea>

                        <button type="submit" class="btn btn-success waves-effect waves-light">Submit</button>
                        <button type="reset" id="btn_reset" class="btn btn-light waves-effect waves-light">Reset</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-12">
            <div class="card border card-border-light">
                <div class="card-body">
                    <form action="{{ route('master.module.filter') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="row gx-3 gy-2 align-items-center">
                            <div class="col-sm-4">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                            </div>
                            <div class="col-auto" style="margin-top: 36px;">
                                <button type="button" class="btn btn-light btn-label waves-effect waves-light" id="btn_reset"><i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Reset</button>&nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary btn-label waves-effect waves-light" id="btn-filter"><i class="ri-search-line label-icon align-middle fs-16 me-2"></i> Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">List {{ $title }}</h5>
                    <div>
                        <a class="btn btn-primary btn-label waves-effect waves-light" href="{{ route('master.module.create') }}"><i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Add</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableDT_module" class="display table table-bordered dt-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Icon</th>
                                <th>URL</th>
                                <th>Position</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                foreach ($data as $row) {
                            ?>
                                <tr>
                                    <th>{{ $no }}</th>
                                    <th>{{ $row->name }}</th>
                                    <th>{{ $row->module_icon }}</th>
                                    <th>{{ $row->module_url }}</th>
                                    <th>{{ $row->module_position }}</th>
                                    <th>{{ $row->module_description }}</th>
                                    <th><span class="badge text-bg-{{ ($row->module_status == 1 ? "success" : "danger") }}">{{ ($row->module_status == 1 ? "Active" : "Inactive") }}</span></th>
                                    <th>{{ date('d F Y', strtotime($row->created_at)) }}</th>
                                    <th>
                                        <button type="button" class="btn btn-sm btn-warning modalConfirmEdit" data-bs-toggle="modal" data-bs-title="Edit" data-id_edit="{{ $row->id }}"><i class="ri-edit-2-line align-middle"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger modalConfirmDelete" data-bs-toggle="modal" data-bs-title="Delete" data-id_delete="{{ $row->id }}"><i class="ri-delete-bin-line align-middle"></i></button>
                                    </th>
                                </tr>
                            <?php
                                    $no++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
        <!--end col-->
    </div>
    <!--end row-->

    @include('master.module.confirm_edit')
    @include('master.module.confirm_delete')

@endsection

@section('script')
    <script type="text/javascript">
        // DATATABLE
        $('#btn_reset').click(function() {
            window.location.href = "{{ route('master.module') }}";
        });

        $('#tableDT_module').DataTable({
            lengthMenu: [10, 25, 50, 100],
            responsive: true
        });

        // SHOW MODAL EDIT DATA KATEGORI
        $(document).on('click', '.modalConfirmEdit', function() {
            $('.id_edit').text($(this).data('id_edit'));
            $('#modalConfirmEdit').modal('show');
        });

        // SHOW MODAL DELETE DATA KATEGORI
        $(document).on('click', '.modalConfirmDelete', function() {
            $('.id_delete').text($(this).data('id_delete'));
            $('#modalConfirmDelete').modal('show');
        });

        // NESTABLE
        $(document).ready(function() {

            var updateOutput = function(e) {

                var list = e.length ? e : $(e.target),
                    output = list.data('output');

                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));

                    var token = $('form').find('input[name=_token]').val();
                    $.post('{{ URL("module-reorder") }}',
                    {
                            output : output.val(),
                            _token: token
                    },
                    function(data) {
                        console.log(output.val());
                    })
                    .done(function() { alert('done'); })
                    .fail(function() {})
                    .always(function() {});
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            // activate Nestable for list 1
            $('#nestable-wrapper').nestable({
                group: 1
            })
            .on('change', updateOutput);

            // output initial serialised data
            updateOutput($('#nestable-wrapper').data('output', $('#nestable-output')));

            $('#nestable-menu').on('click', function(e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });
        });
    </script>
@endsection
