<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeSwitcher" x-init="init()"
    :data-theme="theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('main_config.tagline') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @livewireStyles

    @yield('css')
    @stack('css')
</head>

<body class="min-h-screen bg-base-100 text-base-content antialiased">

    <div data-motion="scroll-progress" class="fixed top-0 left-0 z-60 h-1 w-full bg-primary origin-left"></div>

    <header class="sticky top-0 z-50 border-b border-base-200 bg-base-100/80 backdrop-blur">
        <nav class="navbar max-w-6xl mx-auto px-4">
            <div class="navbar-start">
                <a href="{{ route('main') }}" wire:navigate>
                    <img src="{{ asset('img/logo-clear.png') }}" alt="{{ config('main_config.short_name') }}"
                        class="h-12 w-auto rounded-lg" :class="theme === 'luxury' ? 'invert-100' : 'invert-0'" />
                </a>
            </div>

            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal gap-1 px-1">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="#cara-kerja">Cara Kerja</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                </ul>
            </div>

            <div class="navbar-end gap-2">
                <button type="button" @click="toggle()" class="btn btn-ghost btn-circle" aria-label="Ganti tema">
                    <svg x-show="theme === 'corporate'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                    <svg x-show="theme === 'luxury'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </button>
                <a href="#cta" class="btn btn-primary hidden sm:inline-flex">Mulai Gratis</a>
            </div>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer
        class="footer sm:footer-horizontal bg-base-200 text-base-content border-t border-base-300 px-10 py-12 gap-8">
        <aside class="max-w-xs">
            <span class="text-lg font-bold">{{ config('main_config.short_name') }}</span>
            <p class="mt-2 text-sm opacity-70">
                Platform pendataan dan rekap otomatis untuk tim modern.
            </p>
        </aside>
        <nav>
            <h6 class="footer-title">Produk</h6>
            <a href="#fitur" class="link link-hover">Fitur</a>
            <a href="#cara-kerja" class="link link-hover">Cara Kerja</a>
            <a href="#cta" class="link link-hover">Harga</a>
        </nav>
        <nav>
            <h6 class="footer-title">Perusahaan</h6>
            <a href="#home" class="link link-hover">Tentang</a>
            <a href="#testimoni" class="link link-hover">Testimoni</a>
            <a href="#home" class="link link-hover">Kontak</a>
        </nav>
        <nav>
            <h6 class="footer-title">Bantuan</h6>
            <a href="#home" class="link link-hover">Dokumentasi</a>
            <a href="#home" class="link link-hover">Pusat Bantuan</a>
        </nav>
    </footer>

    <div class="bg-base-300 text-center text-sm py-4 text-base-content/70">
        © {{ date('Y') }} {{ config('main_config.short_name') }} — Dibangun dengan Laravel, Livewire & daisyUI.
    </div>

    @livewireScripts

    @yield('script')
    @stack('script')
</body>

</html>
