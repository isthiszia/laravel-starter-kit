@props(['name', 'title' => 'Modal', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'w-[95vw] sm:max-w-md',
        'md' => 'w-[95vw] sm:max-w-2xl',
        'lg' => 'w-[95vw] sm:max-w-4xl',
        'xl' => 'w-[95vw] sm:max-w-6xl',
        'vxl' => 'w-[95vw] sm:max-w-7xl',
        'full' => 'w-[98vw] h-[95vh]',
    ];
@endphp

<flux:modal :name="$name" dismissible="false" class="mx-auto mt-4 sm:mt-10 {{ $sizes[$size] ?? $sizes['md'] }}">
    <div class="space-y-6 p-4 sm:p-6 overflow-auto max-h-[85vh]">
        <flux:heading size="lg">
            {{ $title }}
        </flux:heading>
        <hr>

        {{ $slot }}
    </div>
</flux:modal>
