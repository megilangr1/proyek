<div {{ $attributes->merge(['class' => 'flex items-center justify-between cursor-pointer hover:scale-105 transition-transform duration-300']) }}
    wire:click="$dispatch('setOrderBy', { field: '{{ $field }}' })">
    <span class="flex-auto">
        {{ $label }}
    </span>

    @if ($orderBy === $field)
        @if ($orderType === 'ASC')
            <x-lucide-arrow-down-a-z class="shrink-0 size-3.5" />
        @else
            <x-lucide-arrow-down-z-a class="shrink-0 size-3.5" />
        @endif
    @else
        <x-lucide-arrow-up-down class="shrink-0 size-3.5" />
    @endif
</div>
