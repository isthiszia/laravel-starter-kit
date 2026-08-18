<x-modals.form-modal name="edit-user-modal" :title="$title ?? 'Edit User'" :size="$size ?? 'lg'">
    <form id="addUserForm" class="space-y-6">
        @csrf



        <div class="flex justify-end border-t pt-4">
            <flux:button type="submit" variant="primary">
                Save User
            </flux:button>
        </div>
    </form>
</x-modals.form-modal>
