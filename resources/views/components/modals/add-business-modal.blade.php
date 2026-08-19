<x-modals.form-modal name="add-business-modal" :title="$title ?? 'Add Business'" :size="$size ?? 'lg'">
    <form id="addBusinessForm" class="space-y-6" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <flux:input name="name" label="Name" placeholder="Enter name" required />

            <flux:input name="email" type="email" label="Email" placeholder="Enter email" autocomplete="off"
                required />

            <flux:input name="phone" type="number" label="Phone" placeholder="Enter phone" autocomplete="off"
                required />

            <flux:input name="address" type="text" label="Address" placeholder="Enter address" autocomplete="off"
                required />

            <div class="md:col-span-2">
                <label for="business_logo" class="block mb-2 text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Business Logo
                </label>

                <input id="business_logo" name="logo" type="file"
                    accept="image/png,image/jpeg,image/jpg,image/webp"
                    class="block w-full rounded-lg border border-zinc-300 bg-white text-sm text-zinc-700
                           file:mr-4 file:border-0 file:bg-zinc-100 file:px-4 file:py-2
                           file:text-sm file:font-medium
                           hover:file:bg-zinc-200
                           dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300
                           dark:file:bg-zinc-800 dark:file:text-zinc-200">

                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                    PNG, JPG, JPEG or WEBP. Maximum size: 2MB.
                </p>
            </div>

        </div>

        <div class="flex justify-end border-t pt-4">
            <flux:button type="submit" variant="primary">
                Save Business
            </flux:button>
        </div>
    </form>
</x-modals.form-modal>

<script>
    $(document).on('submit', '#addBusinessForm', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('business.store') }}",
            method: "POST",
            data: formData,

            processData: false,
            contentType: false,

            success: function(response) {

                form[0].reset();

                if (window.Flux) {
                    Flux.modal('add-business-modal').close();
                }

                Flux.toast({
                    variant: 'success',
                    text: response.message ?? 'Business created successfully',
                });

                if (window.Livewire) {
                    Livewire.dispatch('pg:eventRefresh-businessTable');
                }
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
