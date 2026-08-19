<x-modals.form-modal name="add-business-modal" :title="$title ?? 'Add Business'" :size="$size ?? 'lg'">
    <form id="addBusinessForm" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <flux:input name="name" label="Name" placeholder="Enter name" required />

            <flux:input name="email" type="email" label="Email" placeholder="Enter email" autocomplete="off"
                required />

            <flux:input name="phone" type="number" label="Phone" placeholder="Enter phone" autocomplete="off"
                required />

            <flux:input name="address" type="test" label="Address" placeholder="Enter address" autocomplete="off"
                required />

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

        $.ajax({
            url: "{{ route('business.store') }}",
            method: "POST",
            data: form.serialize(),

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
