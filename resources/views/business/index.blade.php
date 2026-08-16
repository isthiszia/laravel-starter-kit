<x-layouts::app :title="__('Business')">
    <div class="relative mb-6 w-full">

        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl" level="1">
                    {{ __('Business Management') }}
                </flux:heading>

                <flux:subheading size="lg">
                    {{ __('View and manage all businesses.') }}
                </flux:subheading>
            </div>
            <flux:modal.trigger name="add-business-modal">
                <flux:button variant="primary">
                    Add Business
                </flux:button>
            </flux:modal.trigger>


        </div>

        <flux:separator variant="subtle" />

        <flux:separator variant="subtle" />
    </div>

    <div class="rounded-xl border border-zinc-100 bg-white">
        <livewire:BusinessTable />
    </div>

    {{-- Attach modal --}}
    <x-modals.add-business-modal title="Add Business" size="xl" />
</x-layouts::app>
