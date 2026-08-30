<div id="home" class="overflow-hidden">

    {{-- ===================== HERO ===================== --}}
    <section class="relative">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="bg-grid absolute inset-0 opacity-60"></div>
            <div data-motion="scroll-parallax" data-motion-speed="0.15"
                class="bg-aurora absolute -top-40 -right-24 h-112 w-md opacity-50"></div>
            <div data-motion="gradient-pan" class="bg-aurora absolute top-48 -left-32 h-96 w-96 opacity-40"></div>
        </div>

        <div class="hero min-h-[88vh]">
            <div class="hero-content flex-col-reverse gap-12 max-w-6xl w-full py-16 lg:flex-row-reverse">
                <div class="w-full flex-1" data-motion="float-in">
                    <div class="glass-card--primary rounded-2xl p-2 shadow-2xl" data-motion="glow-pulse"
                        data-motion-glow="primary" data-motion-duration="4.5">
                        <div class="rounded-xl bg-base-100 p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="{{ asset('img/logo.png') }}" alt="logo" class="h-7 w-7 rounded-md" />
                                    <div>
                                        <p class="text-sm opacity-60">Rekap Bulanan</p>
                                        <p class="text-sm font-bold">Mei 2026</p>
                                    </div>
                                </div>
                                <span class="badge badge-soft badge-success gap-1">
                                    <span class="h-2 w-2 rounded-full bg-success"></span> Live
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="rounded-box bg-base-200/70 p-3">
                                    <p class="text-xs opacity-60">Total Data</p>
                                    <p class="text-xl font-bold">1.284</p>
                                </div>
                                <div class="rounded-box bg-base-200/70 p-3">
                                    <p class="text-xs opacity-60">Selesai</p>
                                    <p class="text-xl font-bold">1.120</p>
                                </div>
                                <div class="rounded-box bg-base-200/70 p-3">
                                    <p class="text-xs opacity-60">Pending</p>
                                    <p class="text-xl font-bold">164</p>
                                </div>
                            </div>

                            <table class="table table-sm mt-2">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>1</th>
                                        <td>Penjualan</td>
                                        <td><span class="badge badge-soft badge-success">Selesai</span></td>
                                    </tr>
                                    <tr>
                                        <th>2</th>
                                        <td>Kehadiran</td>
                                        <td><span class="badge badge-soft badge-success">Selesai</span></td>
                                    </tr>
                                    <tr>
                                        <th>3</th>
                                        <td>Inventaris</td>
                                        <td><span class="badge badge-soft badge-warning">Pending</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="w-full flex-1">
                    <span class="badge badge-soft badge-primary mb-5 gap-1" data-motion="fade-up">
                        <x-lucide-sparkles class="h-3.5 w-3.5" />
                        Solusi digital untuk pendataan
                    </span>

                    <h1 data-motion="text-split"
                        class="text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl xl:text-6xl">
                        Pendataan dan Rekap <span class="text-gradient">Pintar</span>
                    </h1>

                    <p data-motion="fade-up" data-motion-delay="0.2" class="mt-6 max-w-xl text-lg text-base-content/70">
                        Catat, kelola, dan rekap data Anda secara otomatis. Hemat waktu, kurangi
                        kesalahan, dan dapatkan laporan yang rapi hanya dalam beberapa klik.
                    </p>

                    <div data-motion="pop-in" data-motion-delay="0.4" class="mt-8 flex flex-wrap gap-4">
                        <a href="#cta" class="btn btn-primary btn-lg" data-motion="hover-lift">
                            Mulai sekarang
                        </a>
                        <a href="#fitur" class="btn btn-outline btn-lg" data-motion="hover-lift">
                            Lihat fitur
                        </a>
                    </div>

                    <div data-motion="fade-up" data-motion-delay="0.55"
                        class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4 text-sm text-base-content/60">
                        <div>
                            <p data-motion="count-up" data-motion-to="10000" data-motion-suffix="rb+"
                                class="text-2xl font-bold text-base-content">0</p>
                            <p>data tercatat</p>
                        </div>
                        <div class="hidden h-8 w-px bg-base-300 sm:block"></div>
                        <div>
                            <p data-motion="count-up" data-motion-to="500" data-motion-suffix="+"
                                class="text-2xl font-bold text-base-content">0</p>
                            <p>pengguna aktif</p>
                        </div>
                        <div class="hidden h-8 w-px bg-base-300 sm:block"></div>
                        <div>
                            <p data-motion="count-up" data-motion-to="99.9" data-motion-decimals="1"
                                data-motion-suffix="%" class="text-2xl font-bold text-base-content">0</p>
                            <p>akurasi rekap</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FITUR ===================== --}}
    <section id="fitur" class="max-w-6xl mx-auto px-4 py-24">
        <div class="mx-auto max-w-2xl text-center">
            <h2 data-motion="fade-up" class="text-3xl font-bold sm:text-4xl">Semua yang Anda butuhkan</h2>
            <p data-motion="fade-up" data-motion-delay="0.1" class="mt-4 text-base-content/70">
                Fitur lengkap untuk memudahkan pendataan, dari input hingga laporan akhir.
            </p>
        </div>

        <div data-motion="stagger" class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="glass-card rounded-2xl" data-motion="tilt-3d">
                <div class="card-body">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-primary/15 to-secondary/15 text-primary">
                        <x-lucide-clipboard-list class="h-6 w-6" />
                    </div>
                    <h3 class="card-title">Input Cepat & Mudah</h3>
                    <p class="text-base-content/70">Form pintar dengan validasi otomatis, sehingga pencatatan data jadi
                        cepat dan bebas salah ketik.</p>
                </div>
            </div>
            <div class="glass-card rounded-2xl" data-motion="tilt-3d">
                <div class="card-body">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-primary/15 to-secondary/15 text-primary">
                        <x-lucide-zap class="h-6 w-6" />
                    </div>
                    <h3 class="card-title">Rekap Otomatis</h3>
                    <p class="text-base-content/70">Data langsung diolah menjadi ringkasan, grafik, dan tren tanpa
                        perlu
                        hitung manual.</p>
                </div>
            </div>
            <div class="glass-card rounded-2xl" data-motion="tilt-3d">
                <div class="card-body">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-primary/15 to-secondary/15 text-primary">
                        <x-lucide-file-bar-chart class="h-6 w-6" />
                    </div>
                    <h3 class="card-title">Laporan Fleksibel</h3>
                    <p class="text-base-content/70">Ekspor ke PDF, Excel, atau bagikan tautan laporan ke tim Anda kapan
                        saja.</p>
                </div>
            </div>
            <div class="glass-card rounded-2xl" data-motion="tilt-3d">
                <div class="card-body">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-primary/15 to-secondary/15 text-primary">
                        <x-lucide-shield-check class="h-6 w-6" />
                    </div>
                    <h3 class="card-title">Aman & Terpusat</h3>
                    <p class="text-base-content/70">Seluruh data tersimpan rapi di satu tempat dengan kendali akses
                        berbasis peran.</p>
                </div>
            </div>
            <div class="glass-card rounded-2xl" data-motion="tilt-3d">
                <div class="card-body">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-primary/15 to-secondary/15 text-primary">
                        <x-lucide-users class="h-6 w-6" />
                    </div>
                    <h3 class="card-title">Kolaborasi Tim</h3>
                    <p class="text-base-content/70">Undang rekan, bagi tugas pendataan, dan pantau progres secara
                        real-time.</p>
                </div>
            </div>
            <div class="glass-card rounded-2xl" data-motion="tilt-3d">
                <div class="card-body">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-linear-to-br from-primary/15 to-secondary/15 text-primary">
                        <x-lucide-smartphone class="h-6 w-6" />
                    </div>
                    <h3 class="card-title">Akses Di Mana Saja</h3>
                    <p class="text-base-content/70">Tampilan responsif yang nyaman digunakan dari desktop maupun
                        perangkat seluler.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CARA KERJA ===================== --}}
    <section id="cara-kerja" class="bg-base-200/50 py-24">
        <div class="mx-auto max-w-6xl px-4">
            <div class="mx-auto max-w-2xl text-center">
                <h2 data-motion="fade-up" class="text-3xl font-bold sm:text-4xl">Cara kerjanya</h2>
                <p data-motion="fade-up" data-motion-delay="0.1" class="mt-4 text-base-content/70">
                    Tiga langkah sederhana untuk merapikan data Anda.
                </p>
            </div>

            <ul data-motion="stagger" class="mt-14 steps steps-vertical lg:steps-horizontal w-full">
                <li class="step step-primary">
                    <div class="glass-card rounded-2xl p-4 text-left lg:text-center" data-motion="reveal">
                        <h3 class="font-bold">Daftar & Buat Workspace</h3>
                        <p class="mt-1 text-sm text-base-content/70">Buat akun dan siapkan ruang kerja untuk tim Anda.
                        </p>
                    </div>
                </li>
                <li class="step step-primary">
                    <div class="glass-card rounded-2xl p-4 text-left lg:text-center" data-motion="reveal">
                        <h3 class="font-bold">Input & Kumpulkan Data</h3>
                        <p class="mt-1 text-sm text-base-content/70">Catat data lewat form pintar, dari mana saja.</p>
                    </div>
                </li>
                <li class="step step-primary">
                    <div class="glass-card rounded-2xl p-4 text-left lg:text-center" data-motion="reveal">
                        <h3 class="font-bold">Dapatkan Rekap & Laporan</h3>
                        <p class="mt-1 text-sm text-base-content/70">Lihat ringkasan otomatis dan ekspor laporannya.
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    {{-- ===================== TECH STACK ===================== --}}
    <section class="border-y border-base-200 bg-base-200/40 py-16">
        <div class="mx-auto max-w-6xl px-4">
            <p data-motion="fade-up"
                class="mb-8 text-center text-sm font-medium uppercase tracking-wider text-base-content/50">
                Didukung oleh teknologi modern
            </p>
            <div data-store-banner-swiper class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="glass-card rounded-2xl text-center">
                            <div class="card-body items-center text-center">
                                <div class="text-primary"><x-lucide-server class="h-9 w-9" /></div>
                                <h3 class="card-title">Laravel</h3>
                                <p class="text-base-content/70">PHP framework yang ekspresif, aman, dan powerful.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="glass-card rounded-2xl text-center">
                            <div class="card-body items-center text-center">
                                <div class="text-primary"><x-lucide-zap class="h-9 w-9" /></div>
                                <h3 class="card-title">Livewire</h3>
                                <p class="text-base-content/70">UI dinamis dan reaktif tanpa menulis JavaScript.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="glass-card rounded-2xl text-center">
                            <div class="card-body items-center text-center">
                                <div class="text-primary"><x-lucide-palette class="h-9 w-9" /></div>
                                <h3 class="card-title">Tailwind CSS</h3>
                                <p class="text-base-content/70">Styling utility-first yang cepat dan konsisten.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="glass-card rounded-2xl text-center">
                            <div class="card-body items-center text-center">
                                <div class="text-primary"><x-lucide-flower class="h-9 w-9" /></div>
                                <h3 class="card-title">daisyUI</h3>
                                <p class="text-base-content/70">Komponen siap pakai dengan dukungan tema terang &
                                    gelap.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="glass-card rounded-2xl text-center">
                            <div class="card-body items-center text-center">
                                <div class="text-primary"><x-lucide-bot class="h-9 w-9" /></div>
                                <h3 class="card-title">Alpine.js</h3>
                                <p class="text-base-content/70">Interaksi ringan di sisi klien hanya dengan atribut.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="glass-card rounded-2xl text-center">
                            <div class="card-body items-center text-center">
                                <div class="text-primary"><x-lucide-film class="h-9 w-9" /></div>
                                <h3 class="card-title">Motion</h3>
                                <p class="text-base-content/70">Animasi halus yang membuat halaman terasa hidup.</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="glass-card rounded-2xl text-center">
                            <div class="card-body items-center text-center">
                                <div class="text-primary"><x-lucide-database class="h-9 w-9" /></div>
                                <h3 class="card-title">MySQL</h3>
                                <p class="text-base-content/70">Penyimpanan data yang handal dan terstruktur.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    {{-- ===================== TESTIMONI ===================== --}}
    <section id="testimoni" class="max-w-6xl mx-auto px-4 py-24">
        <div class="mx-auto max-w-2xl text-center">
            <h2 data-motion="fade-up" class="text-3xl font-bold sm:text-4xl">Dipercaya banyak tim</h2>
            <p data-motion="fade-up" data-motion-delay="0.1" class="mt-4 text-base-content/70">
                Cerita dari pengguna yang sudah merapikan datanya bersama kami.
            </p>
        </div>

        <div class="mt-14 grid gap-6 md:grid-cols-3">
            <div data-motion="reveal" class="glass-card rounded-2xl">
                <div class="card-body">
                    <p class="text-base-content/80">"Dulu rekap tiap akhir bulan butuh berhari-hari. Sekarang tinggal
                        klik dan laporan langsung jadi."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="avatar placeholder">
                            <div class="w-10 rounded-full bg-primary text-primary-content">RA</div>
                        </div>
                        <div>
                            <p class="font-semibold">Rina A.</p>
                            <p class="text-sm text-base-content/60">Administrasi Sekolah</p>
                        </div>
                    </div>
                </div>
            </div>
            <div data-motion="reveal" data-motion-delay="0.12" class="glass-card rounded-2xl">
                <div class="card-body">
                    <p class="text-base-content/80">"Tim jadi satu pemahaman soal angka. Datanya terpusat dan gampang
                        dibagikan ke atasan."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="avatar placeholder">
                            <div class="w-10 rounded-full bg-secondary text-secondary-content">BS</div>
                        </div>
                        <div>
                            <p class="font-semibold">Budi S.</p>
                            <p class="text-sm text-base-content/60">Manajer Operasional</p>
                        </div>
                    </div>
                </div>
            </div>
            <div data-motion="reveal" data-motion-delay="0.24" class="glass-card rounded-2xl">
                <div class="card-body">
                    <p class="text-base-content/80">"Antarmukanya bersih dan enak dipakai di HP. Pendataan lapangan
                        jadi jauh lebih mudah."</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="avatar placeholder">
                            <div class="w-10 rounded-full bg-accent text-accent-content">DC</div>
                        </div>
                        <div>
                            <p class="font-semibold">Dewi C.</p>
                            <p class="text-sm text-base-content/60">Surveyor Lapangan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section id="cta" class="max-w-6xl mx-auto px-4 pb-24">
        <div data-motion="blur-scale"
            class="rounded-3xl bg-linear-to-r from-primary via-secondary to-primary p-0.5 shadow-xl">
            <div class="card rounded-3xl border-0 bg-primary text-primary-content">
                <div class="card-body items-center gap-6 py-16 text-center">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('main_config.short_name') }}"
                        class="h-12 w-12 rounded-xl bg-primary-content/10 p-1" />
                    <h2 class="text-3xl font-bold sm:text-5xl">Siap merapikan data Anda?</h2>
                    <p class="max-w-xl opacity-90">
                        Mulai gratis dan rasakan betapa mudahnya pendataan dan rekap yang pintar.
                    </p>
                    <div class="mt-2 flex flex-wrap justify-center gap-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg" data-motion="hover-lift"
                            wire:navigate>
                            Mulai Gratis
                        </a>
                        <a href="#fitur"
                            class="btn btn-outline btn-lg border-primary-content text-primary-content hover:bg-primary-content hover:text-primary"
                            data-motion="hover-lift">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
