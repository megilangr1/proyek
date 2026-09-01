<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeSwitcher" x-init="init()"
    :data-theme="theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Masuk' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @yield('css')
    @stack('css')
</head>

<body
    class="relative min-h-screen overflow-hidden bg-base-100 text-base-content antialiased grid place-items-center px-4">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="bg-grid absolute inset-0 opacity-60"></div>
        <div data-motion="gradient-pan" class="bg-aurora absolute -top-32 -left-24 h-80 w-80 opacity-50"></div>
        <div data-motion="scroll-parallax" data-motion-speed="0.2"
            class="bg-aurora absolute bottom-0 -right-24 h-96 w-96 opacity-40"></div>
    </div>

    <main class="relative z-10 w-full max-w-md">
        {{ $slot }}
    </main>

    @livewireScripts
    @yield('script')
    @stack('script')
</body>

</html>
