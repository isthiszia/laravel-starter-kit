<x-modals.form-modal name="edit-business-modal" :title="$title ?? 'Edit Business'" :size="$size ?? 'lg'">
    <form id="editBusinessForm" class="space-y-6">
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
    document.addEventListener('livewire:init', () => {
        Livewire.on('edit-business-modal', (event) => {
            let business = event.business;
            $('#edit_business_id').val(business.id);
            $('#edit_business_name').val(business.name);
            $('#edit_business_email').val(business.email);
            $('#edit_business_phone').val(business.phone);
            $('#edit_business_address').val(business.address);
            if (window.Flux) {
                Flux.modal('edit-business-modal').show();
            }
        });
    });

    $(document).on('submit', '#editBusinessForm', function(e) {
        e.preventDefault();
        let form = $(this);
        let businessId = $('#edit_business_id').val();
        let submitButton = $('#editBusinessSubmitBtn');
        if (!businessId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Business ID is missing.'
            });
            return;
        }

        submitButton.prop('disabled', true);

        $.ajax({
            url: "{{ route('business.update', ':id') }}"
                .replace(':id', businessId),
            method: "POST",
            data: form.serialize(),
            success: function(response) {

                if (window.Flux) {
                    Flux.modal('edit-business-modal').close();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message ??
                        'Business updated successfully',
                    timer: 1800,
                    showConfirmButton: false
                });

                if (window.Livewire) {
                    Livewire.dispatch(
                        'pg:eventRefresh-businessTable'
                    );
                }

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
                }

                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            },

            complete: function() {
                submitButton.prop('disabled', false);
            }
        });
    });
</script>
