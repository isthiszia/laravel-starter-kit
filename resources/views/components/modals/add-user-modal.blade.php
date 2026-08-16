<x-modals.form-modal name="add-user-modal" :title="$title ?? 'Add User'" :size="$size ?? 'lg'">
    <form id="addUserForm" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <flux:input name="name" label="Name" placeholder="Enter name" required/>

            <flux:input name="email" type="email" label="Email" placeholder="Enter email" autocomplete="off" required/>

            <flux:input name="password" type="password" label="Password" placeholder="Enter password"
                autocomplete="new-password" required/>

            <flux:select name="business_id" label="Business" required>
                <option value="">Select Business</option>

                @foreach ($businesses as $business)
                    <option value="{{ $business->id }}">
                        {{ $business->name }}
                    </option>
                @endforeach
            </flux:select>

            <div class="md:col-span-2">
                <flux:select name="role" label="Role" required>
                    <option value="">Select Role</option>

                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">
                            {{ $role->name }}
                        </option>
                    @endforeach
                </flux:select>
            </div>

        </div>

        <div class="flex justify-end border-t pt-4">
            <flux:button type="submit" variant="primary">
                Save User
            </flux:button>
        </div>
    </form>
</x-modals.form-modal>

<script>
    $(document).on('submit', '#addUserForm', function(e) {
        e.preventDefault();
        console.log('Form submitted');
        let form = $(this);

        $.ajax({
            url: "{{ route('user.store') }}",
            method: "POST",
            data: form.serialize(),

            success: function(response) {

                form[0].reset();

                if (window.Flux) {
                    Flux.modal('add-user-modal').close();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message ?? 'User created successfully'
                });

                if (window.Livewire) {
                    Livewire.dispatch('pg:eventRefresh-userTable');
                }
            },

            error: function(xhr) {

                let message = 'Something went wrong';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    });
</script>
