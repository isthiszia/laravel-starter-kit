<x-modals.form-modal name="edit-business-modal" :title="$title ?? 'Edit Business'" :size="$size ?? 'lg'">
    <form id="editBusinessForm" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <input type="hidden" name="business_id" id="edit_business_id">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <flux:input name="name" id="edit_business_name" label="Name" placeholder="Enter name" required />

            <flux:input name="email" id="edit_business_email" type="email" label="Email" placeholder="Enter email"
                autocomplete="off" required />

            <flux:input name="phone" id="edit_business_phone" type="text" label="Phone" placeholder="Enter phone"
                autocomplete="off" required />

            <flux:input name="address" id="edit_business_address" type="text" label="Address"
                placeholder="Enter address" autocomplete="off" required />

            <div class="md:col-span-2">

                <label for="edit_business_logo" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Business Logo
                </label>

                <div id="existingBusinessLogo" class="mb-3 hidden">
                    <p class="mb-2 text-xs text-zinc-500 dark:text-zinc-400">
                        Current Logo
                    </p>

                    <img id="edit_business_logo_preview" src="" alt="Business Logo"
                        class="h-20 w-20 rounded-lg border border-zinc-300 object-cover dark:border-zinc-700">
                </div>

                <input id="edit_business_logo" name="logo" type="file"
                    accept="image/png,image/jpeg,image/jpg,image/webp"
                    class="block w-full rounded-lg border border-zinc-300 bg-white text-sm text-zinc-700
                           file:mr-4 file:border-0 file:bg-zinc-100 file:px-4 file:py-2
                           file:text-sm file:font-medium
                           hover:file:bg-zinc-200
                           dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300
                           dark:file:bg-zinc-800 dark:file:text-zinc-200">

                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                    PNG, JPG, JPEG or WEBP. Maximum size: 2MB.
                    Leave empty to keep the current logo.
                </p>

            </div>

        </div>

        <div class="flex justify-end gap-3 border-t pt-4">

            <flux:modal.close>
                <flux:button type="button" variant="ghost">
                    Cancel
                </flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="primary" id="editBusinessSubmitBtn">
                Save Changes
            </flux:button>

        </div>
    </form>
</x-modals.form-modal>

<script>
    $(document).on('submit', '#editBusinessForm', function(e) {
        e.preventDefault();

        let form = $(this);
        let businessId = $('#edit_business_id').val();
        let submitButton = $('#editBusinessSubmitBtn');

        if (!businessId) {
            Flux.toast({
                variant: 'error',
                text: 'Business ID is missing.',
            });

            return;
        }

        let formData = new FormData(this);

        submitButton.prop('disabled', true);

        $.ajax({
            url: "{{ route('business.update', ':id') }}"
                .replace(':id', businessId),

            method: "POST",

            data: formData,

            processData: false,
            contentType: false,

            success: function(response) {

                if (window.Flux) {
                    Flux.modal('edit-business-modal').close();
                }

                Flux.toast({
                    variant: 'success',
                    text: response.message ?? 'Business updated successfully',
                });

                if (window.Livewire) {
                    Livewire.dispatch(
                        'pg:eventRefresh-businessTable'
                    );
                }

                form[0].reset();

                $('#existingBusinessLogo').addClass('hidden');
                $('#edit_business_logo_preview').attr('src', '');

            },

            error: function(xhr) {

                let message = 'Something went wrong.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = [];

                    Object.keys(errors).forEach(function(field) {
                        errors[field].forEach(function(error) {
                            errorMessages.push(error);
                        });
                    });

                    message = errorMessages.join('\n');

                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Flux.toast({
                    variant: 'error',
                    text: message,
                });
            },

            complete: function() {
                submitButton.prop('disabled', false);
            }
        });
    });
</script>
