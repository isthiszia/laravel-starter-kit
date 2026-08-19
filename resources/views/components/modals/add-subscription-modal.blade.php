<x-modals.form-modal name="add-subscription-modal" :title="$title ?? 'Add Subscription'" :size="$size ?? 'lg'">
    <form id="addSubscriptionForm" class="space-y-6">
        @csrf

        <flux:select name="business_id" label="Business" required>
            <option value="">Select Business</option>

            @foreach ($businesses as $business)
                <option value="{{ $business->id }}">
                    {{ $business->name }}
                </option>
            @endforeach
        </flux:select>

        <div class="flex justify-end border-t pt-4">
            <flux:button type="submit" variant="primary">
                Save Subscription
            </flux:button>
        </div>
    </form>
</x-modals.form-modal>

<script>
    $(document).on('submit', '#addSubscriptionForm', function(e) {

        e.preventDefault();

        let form = $(this);
        let button = $('#saveSubscriptionBtn');

        form.find('.ajax-error').remove();

        button.prop('disabled', true);

        $.ajax({
            url: "{{ route('subscription.store') }}",
            type: "POST",
            data: form.serialize(),

            success: function(response) {

                form[0].reset();

                if (window.Flux) {
                    Flux.modal('add-subscription-modal').close();
                }

                Flux.toast({
                    variant: 'success',
                    text: response.message ?? 'Subscription created successfully',
                });
                if (window.Livewire) {
                    Livewire.dispatch('pg:eventRefresh-subscriptionTable');
                }
                button.prop('disabled', false);
            },

            error: function(xhr) {

                button.prop('disabled', false);

                let message = 'Something went wrong.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function(field, messages) {

                        let input = form.find('[name="' + field + '"]');

                        if (input.length) {

                            input.after(
                                '<div class="ajax-error text-sm text-red-600 mt-1">' +
                                messages[0] +
                                '</div>'
                            );
                        }
                    });

                    message = 'Please check the form and correct the errors.';
                }
                else if (xhr.responseJSON?.message) {
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
