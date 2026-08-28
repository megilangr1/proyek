<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeSwitcher" x-init="init()"
    :data-theme="theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('layouts.css')
</head>

<body class="antialiased min-h-screen flex flex-col bg-base-100 text-base-content">
    <main class="w-full" id="main-content">
        <div class="drawer lg:drawer-open">
            <input id="sidebar" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content w-full h-screen max-h-screen overflow-y-auto flex flex-col">
                @include('layouts.navbar')

                <div class="w-full flex-1 px-4 py-3">
                    @yield('content')

                    {{ $slot ?? '' }}
                </div>
            </div>
            <div class="drawer-side border-r-2 border-r-base-300">
                <label for="sidebar" aria-label="close sidebar" class="drawer-overlay"></label>

                @include('layouts.sidebar')
            </div>
        </div>
    </main>

    @include('layouts.script')
</body>

</html>
