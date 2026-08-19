<x-modals.form-modal name="add-permission-modal" :title="$title ?? 'Add Permission'" :size="$size ?? 'lg'">
    <form id="addPermissionForm" class="space-y-6">
        @csrf

        <flux:input name="name" label="Permission Names" placeholder="user, edit-user..." required />

        <p class="text-sm text-gray-500 mt-2">
            Enter multiple permissions separated by commas (,).
        </p>

        <div class="flex justify-end border-t pt-4">
            <flux:button type="submit" variant="primary">
                Save Permission
            </flux:button>
        </div>
    </form>
</x-modals.form-modal>

<script>
    $(document).on('submit', '#addPermissionForm', function(e) {
        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: "{{ route('permission.store') }}",
            method: "POST",
            data: form.serialize(),

            success: function(response) {

                form[0].reset();

                if (window.Flux) {
                    Flux.modal('add-permission-modal').close();
                }

                Flux.toast({
                    variant: 'success',
                    text: response.message ?? 'Permission created successfully',
                });
            },

            error: function(xhr) {

                let message = 'Something went wrong';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Flux.toast({
                    variant: 'success',
                    text: message,
                });
            }
        });
    });
</script>
