<x-modals.form-modal name="add-role-modal" :title="$title ?? 'Add Role'" :size="$size ?? 'lg'">
    <form id="addRoleForm" class="space-y-6">
        @csrf

        <flux:input name="name" label="Role Names" placeholder="Admin, User..." required />

        <p class="text-sm text-gray-500 mt-2">
            Enter multiple Roles separated by commas (,).
        </p>

        <div class="flex justify-end border-t pt-4">
            <flux:button type="submit" variant="primary">
                Save Role
            </flux:button>
        </div>
    </form>
</x-modals.form-modal>

<script>
    $(document).on('submit', '#addRoleForm', function(e) {
        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: "{{ route('role.store') }}",
            method: "POST",
            data: form.serialize(),

            success: function(response) {

                form[0].reset();

                if (window.Flux) {
                    Flux.modal('add-role-modal').close();
                }

                Flux.toast({
                    variant: 'success',
                    text: response.message ?? 'Role created successfully',
                });
            },

            error: function(xhr) {

                let message = 'Something went wrong';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Flux.toast({
                    variant: 'error',
                    text: message,
                });
            }
        });
    });
</script>
