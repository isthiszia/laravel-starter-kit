<x-layouts::app :title="__('Business')">
    <div class="relative mb-6 w-full">

        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl" level="1">
                    {{ __('Monthly Subscription') }}
                </flux:heading>

                <flux:subheading size="lg">
                    {{ __('View and manage all businesses subscription.') }}
                </flux:subheading>
            </div>
            <flux:modal.trigger name="add-subscription-modal">
                <flux:button variant="primary">
                    Activate
                </flux:button>
            </flux:modal.trigger>


        </div>

        <flux:separator variant="subtle" />

        <flux:separator variant="subtle" />
    </div>

    <div class="rounded-xl border border-zinc-100 bg-white">
        <livewire:SubscriptionTable />
    </div>

    {{-- Attach modal --}}
    <x-modals.add-subscription-modal title="Add Subscription" size="sm" :businesses="$businesses"/>
</x-layouts::app>
