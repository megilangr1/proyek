<div class="navbar bg-base-100 shadow-sm gap-2 border-b border-b-base-300">
    <div class="flex-none">
        <label for="sidebar" class="btn btn-square btn-ghost drawer-button lg:hidden">
            <x-lucide-text-align-justify class="size-5 inline-block stroke-current" />
        </label>
    </div>
    <div class="flex-1 flex flex-col gap-2">
        <h1 class="text-xs font-semibold">
            {{ config('app.name') }}
        </h1>
        <h5 class="text-[10px] font-semibold hidden lg:block">MR Solution</h5>
    </div>
    <div class="flex-none">
        <button type="button" @click="toggle()" class="btn btn-ghost btn-circle" aria-label="Ganti tema">
            <svg x-show="theme === 'corporate'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21.752 15.002A9.72 9.72 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
            <svg x-show="theme === 'luxury'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
            </svg>
        </button>

        <div class="dropdown dropdown-bottom dropdown-end">
            <button class="btn btn-square btn-ghost" tabindex="0">
                <x-lucide-ellipsis class="size-5 inline-block stroke-current" />
            </button>

            <div tabindex="-1"
                class="dropdown-content menu bg-base-100 rounded-box border border-base-300 z-2 w-52 p-2 shadow-2xl mt-2">
                <div class="flex flex-col gap-3">
                    <a href="{{ '#' }}"
                        class="inline-flex justify-start items-center gap-2 hover:bg-neutral/5 px-3 py-2 cursor-pointer">
                        <x-lucide-user class="size-4 shrink-0" />

                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-xs sm:btn-sm btn-neutral w-full">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
