@props([
    'name',
    'title' => 'Modal',
    'size' => 'md',
])

<flux:modal
    :name="$name"
    dismissible="false"
    class="mt-10 custom-modal modal-{{ $size }}"
    >
    <div class="space-y-6">
        <flux:heading size="lg">
            {{ $title }}
        </flux:heading>

        {{ $slot }}
    </div>
</flux:modal>
