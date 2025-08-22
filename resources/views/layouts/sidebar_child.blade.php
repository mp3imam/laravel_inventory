@if(!empty($menus->menu))
    @foreach($menus->menu as $keys => $sub_menu)
        <li class="nav-item">
            <a class="nav-link menu-link {{ Request::is("".$sub_menu->module_url."*") ? 'active' : '' }}" href="{{ URL("".$sub_menu->module_url."") }}">
                <i class="{{ $sub_menu->module_icon }}"></i> <span>{{ $sub_menu->module_name }}</span>
            </a>
        </li>

        @if(!empty($sub_menu->menu))
            @include('layouts.sidebar_child', ['menus' => $sub_menu])
        @endif
    @endforeach
@endif