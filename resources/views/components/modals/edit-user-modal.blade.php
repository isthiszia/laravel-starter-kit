<x-modals.form-modal name="edit-user-modal" :title="$title ?? 'Edit User'" :size="$size ?? 'lg'">
    <form id="editUserForm" class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="user_id" id="edit_user_id">

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <flux:input name="name" id="edit_name" label="Name" placeholder="Enter name" required />

            <flux:input name="email" id="edit_email" type="email" label="Email" placeholder="Enter email"
                autocomplete="off" required />

            <flux:input name="password" id="edit_password" type="password" label="Password"
                placeholder="Leave blank to keep current password" autocomplete="new-password" />

            <flux:select name="business_id" id="edit_business_id" label="Business" required>
                <option value="">Select Business</option>

                @foreach ($businesses as $business)
                    <option value="{{ $business->id }}">
                        {{ $business->name }}
                    </option>
                @endforeach
            </flux:select>

            <div class="md:col-span-2">
                <flux:select name="role" id="edit_role" label="Role" required>
                    <option value="">Select Role</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">
                            {{ $role->name }}
                        </option>
                    @endforeach
                </flux:select>
            </div>

        </div>

        <div class="flex justify-end gap-3 border-t pt-4">

            <flux:modal.close>
                <flux:button type="button" variant="ghost">
                    Cancel
                </flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="primary" id="editUserSubmitBtn">
                Save Changes
            </flux:button>

        </div>

    </form>
</x-modals.form-modal>


<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('edit-user-modal', (event) => {
            let user = event.user;
            $('#edit_user_id').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_email').val(user.email);
            $('#edit_business_id').val(user.business_id);
            $('#edit_role').val(user.role);
            $('#edit_password').val('');
            if (window.Flux) {
                Flux.modal('edit-user-modal').show();
            }
        });
    });


    $(document).on('submit', '#editUserForm', function(e) {
        e.preventDefault();
        let form = $(this);
        let userId = $('#edit_user_id').val();
        let submitButton = $('#editUserSubmitBtn');
        if (!userId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'User ID is missing.'
            });
            return;
        }

        submitButton.prop('disabled', true);
        $.ajax({
            url: "{{ route('user.update', ':id') }}".replace(':id', userId),
            method: "POST",
            data: form.serialize(),
            success: function(response) {

                if (window.Flux) {
                    Flux.modal('edit-user-modal').close();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message ?? 'User updated successfully',
                    timer: 1800,
                    showConfirmButton: false
                });

                if (window.Livewire) {
                    Livewire.dispatch('pg:eventRefresh-userTable');
                }
            },

            error: function(xhr) {
                let message = 'Something went wrong.';
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
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
