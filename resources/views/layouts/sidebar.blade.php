<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu border-end">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ URL('/') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt="" width="50"
                    height="50">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ URL('/') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo/kejaksaan-logo.png') }}" alt="" width="50"
                    height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                @php
                    $id = auth()
                        ->user()
                        ->roles->pluck('id')
                        ->first();
                    $menu = Module::getModule($id);
                @endphp
                @foreach ($menu as $val)
                    @foreach ($val as $data)
                        @php $subMenu = Module::getSubModule($id, $data['id']); @endphp
                        @php $subMenuURL = Module::getSubModuleUrl($id, $data['id']); @endphp
                        @if ($data->module_icon == '')
                            <li class="menu-title">
                                <i class="{{ $data->module_icon }}"></i>
                                <span>{{ $data->name }}</span>
                            </li>
                        @else
                            @if ($subMenu->isEmpty())
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('' . $data->module_url . '*') ? 'active' : '' }}"
                                        aria-expanded="false" href="{{ URL($data['module_url']) }}">
                                        <i class="{{ $data->module_icon }}"></i> <span>{{ $data->name }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link menu-link collapse {{ Request::is('' . $data->module_url . '*') ? 'active' : '' }} "
                                        href="#{{ $data->module_url }}" data-bs-toggle="collapse" role="button"
                                        aria-expanded="false" aria-controls="{{ $data->module_url }}">
                                        <i class="{{ $data->module_icon }}"></i> <span>{{ $data->name }}</span>
                                    </a>
                                    <div class="menu-dropdown collapse {{ Request::is($subMenuURL) ? 'show' : '' }}"
                                        id="{{ $data->module_url }}" style="">
                                        <ul class="nav nav-sm flex-column">
                                            @foreach ($subMenu as $sub)
                                                <li class="nav-item">
                                                    <a href="{{ URL('' . $sub->module_url . '') }}"
                                                        class="nav-link {{ Request::is('' . $sub->module_url . '*') ? 'active' : '' }}"
                                                        data-key="t-one-page">{{ $sub->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endforeach
                <li class="nav-item">
                    <a class="nav-link menu-link" href="javascript:void();"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-logout-circle-r-line"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
