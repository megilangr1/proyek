<div>
    <div class="grid grid-cols-1 gap-3">
        <div class="flex flex-col sm:flex-row items-start sm:justify-between gap-y-1 gap-x-1">
            <div class="flex-auto flex flex-col gap-2">
                <div class="inline-flex gap-3 items-center justify-start">
                    <h1 class="flex-auto font-semibold text-lg sm:text-xl md:text-xl">
                        {{ $title }}
                    </h1>
                </div>
            </div>

            <div class="w-full sm:w-auto flex flex-row items-center justify-end self-center gap-1">
                {{ $slot }}
            </div>
        </div>
        <hr class="hidden sm:block w-full border-t-2 border-t-slate-300" />
    </div>
</div>
