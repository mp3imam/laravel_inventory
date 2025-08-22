<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light" data-sidebar-image="none">
    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name') }} | Sistem Antrian</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="{{ config('app.name') }}" />
        <meta name="author" content="{{ config('app.name') }}" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo/favicon-kejaksaan.png') }}">
        @include('layouts.head-css')
    </head>

    @yield('body')

        @yield('content')

        @include('layouts.vendor-scripts')
    </body>
</html>
