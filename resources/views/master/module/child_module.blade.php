@if(!empty($menus->menu))
    <ol class="dd-list list-group nested-list nested-sortable" style="cursor: all-scroll;">
        @foreach($menus->menu as $keys => $sub_menu)
            <li class="dd-item list-group-item nested-2" data-id="{{ $sub_menu->id }}" >
                <i class="{{ $sub_menu->module_icon }} fs-18 align-middle text-info me-2"></i>
                <span class="dd-handle align-middle">{{ $sub_menu->name }}</span>
                <div class="dd-option-handle float-end">
                    <button type="button" class="btn btn-sm btn-warning modalConfirmEdit" data-bs-toggle="modal" data-id_edit="{{ $sub_menu->id }}">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger modalConfirmDelete" data-bs-toggle="modal" data-id_delete="{{ $sub_menu->id }}">Delete</button>
                </div>

                @if(!empty($sub_menu->menu))
                    @include('master.module.child_module_second', ['sub_menus' => $sub_menu])
                @endif
            </li>
        @endforeach
    </ol>
@endif
