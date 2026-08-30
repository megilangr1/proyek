<div data-motion="float-in">
    <div class="glass-card--primary rounded-2xl p-2 shadow-2xl" data-motion="glow-pulse" data-motion-glow="primary"
        data-motion-duration="4.5">
        <div class="rounded-xl bg-base-100 p-6 sm:p-8">
            <div class="mb-6 flex flex-col items-center text-center" data-motion="fade-up">
                <img src="{{ asset('img/logo.png') }}" alt="{{ config('main_config.short_name') }}"
                    class="h-14 w-14 rounded-2xl shadow-lg" />
                <h1 class="mt-4 text-2xl font-extrabold">
                    Masuk ke <span class="text-gradient">{{ config('main_config.short_name') }}</span>
                </h1>
                <p class="mt-1 text-sm text-base-content/60">Selamat datang kembali, silakan lanjut.</p>
            </div>

            <form wire:submit="login" class="flex flex-col gap-4">
                <label class="form-control w-full">
                    <span class="label-text mb-1">Email</span>
                    <div class="relative">
                        <input type="email" wire:model="email" placeholder="you@example.com"
                            class="input input-bordered w-full pl-10 @error('email') input-error @enderror"
                            autocomplete="username" />
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50">
                            <x-lucide-mail class="h-5 w-5" />
                        </span>
                    </div>
                    @error('email')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <label class="form-control w-full">
                    <span class="label-text mb-1">Password</span>
                    <div class="relative">
                        <input type="password" wire:model="password" placeholder="••••••••"
                            class="input input-bordered relative z-0 w-full pl-10 @error('password') input-error @enderror"
                            autocomplete="current-password" />

                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-base-content/50">
                            <x-lucide-lock class="h-5 w-5" />
                        </span>
                    </div>
                    @error('password')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <label class="label cursor-pointer justify-start gap-2">
                    <input type="checkbox" wire:model="remember" class="checkbox checkbox-sm checkbox-primary" />
                    <span class="label-text">Ingat saya</span>
                </label>

                <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled"
                    data-motion="hover-lift">
                    <span wire:loading class="loading loading-spinner loading-sm"></span>
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <p class="text-center text-sm opacity-70 mt-4">
        &copy; {{ date('Y') }} {{ config('main_config.short_name') }}
    </p>
</div>
