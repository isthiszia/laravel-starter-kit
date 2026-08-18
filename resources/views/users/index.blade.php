<x-layouts::app :title="__('Users')">
    <div class="relative mb-6 w-full">

        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl" level="1">
                    {{ __('Users Management') }}
                </flux:heading>

                <flux:subheading size="lg">
                    {{ __('View and manage all users and businesses.') }}
                </flux:subheading>
            </div>
            <flux:modal.trigger name="add-user-modal">
                <flux:button variant="primary">
                    Add User
                </flux:button>
            </flux:modal.trigger>


        </div>

        <flux:separator variant="subtle" />

        <flux:separator variant="subtle" />
    </div>

    <div class="power-grid">
        <livewire:user-table />
    </div>

    {{-- Attach modal --}}
    <x-modals.add-user-modal title="Add User" size="xl" :businesses="$businesses" :roles="$roles" />
    <x-modals.edit-user-modal title="Edit User" size="xl" :businesses="$businesses" :roles="$roles" />
</x-layouts::app>
