<x-layouts::app :title="__('Access')">
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">
                    Role & Permission
                </flux:heading>

                <flux:subheading size="lg">
                    Manage roles and permissions.
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:modal.trigger name="add-permission-modal">
                    <flux:button variant="primary">
                        + Permission
                    </flux:button>
                </flux:modal.trigger>

                <flux:modal.trigger name="add-role-modal">
                    <flux:button variant="primary">
                        + Role
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <flux:separator variant="subtle" />

        <div class="overflow-hidden bg-white border rounded-xl border-zinc-100 ">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                Permission
                            </th>

                            @foreach ($roles as $role)
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase text-gray-700">
                                    <div class="flex items-center justify-center gap-2">
                                        <span>{{ $role->name }}</span>

                                        @if ($role->name !== 'Super Admin')
                                            <form class="delete-role-form"
                                                data-url="{{ route('role.destroy', $role->id) }}">
                                                @csrf
                                                <button type="submit" class="text-red-500 hover:text-red-700"
                                                    title="Delete Role">
                                                    <flux:icon.trash class="w-5 h-5" />

                                            </form>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">

                        @foreach ($permissions as $permission)
                            <tr>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-700">
                                            {{ $permission->name }}
                                        </span>

                                        <form class="delete-permission-form"
                                            data-url="{{ route('permission.destroy', $permission->id) }}">

                                            @csrf

                                            <button type="submit" class="text-red-500 hover:text-red-700"
                                                title="Delete Permission">

                                                <flux:icon.trash class="w-5 h-5" />

                                            </button>
                                        </form>
                                    </div>
                                </td>

                                @foreach ($roles as $role)
                                    <td class="px-6 py-4 text-center">

                                        <input type="checkbox"
                                            class="permission-checkbox w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                            data-role-id="{{ $role->id }}"
                                            data-permission-id="{{ $permission->id }}"
                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>

                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>
    {{-- Attach modal --}}
    <x-modals.add-permission-modal title="Add Permission" size="sm" />
    <x-modals.add-role-modal title="Add Role" size="sm" />
</x-layouts::app>
<script>
    $(document).on('submit', '.delete-permission-form', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.data('url');
        Swal.fire({
            title: 'Delete Permission?',
            text: 'This permission will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        form.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    let message = 'Something went wrong.';
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
    });


    $(document).on('change', '.permission-checkbox', function() {
        let checkbox = $(this);
        let roleId = checkbox.data('role-id');
        let permissionId = checkbox.data('permission-id');
        let originalState = checkbox.prop('checked');
        checkbox.prop('disabled', true);
        $.ajax({
            url: "{{ route('permission.update') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                role_id: roleId,
                permission_id: permissionId
            },
            success: function(response) {
                if (response.status) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Updated!',
                    //     text: response.message,
                    //     timer: 1000,
                    //     showConfirmButton: false
                    // });
                }
            },
            error: function(xhr) {
                checkbox.prop('checked', !originalState);
                let message = 'Something went wrong.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            },
            complete: function() {
                checkbox.prop('disabled', false);
            }
        });
    });

    $(document).on('submit', '.delete-role-form', function(e) {
        e.preventDefault();
        let form = $(this);
        let url = form.data('url');
        Swal.fire({
            title: 'Delete Role?',
            text: 'This role will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 800);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong'
                    });
                }
            });
        });
    });
</script>
