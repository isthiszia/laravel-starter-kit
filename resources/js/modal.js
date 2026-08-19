document.addEventListener('click', function (e) {
    const modal = e.target.closest('dialog[data-modal]');

    if (!modal || !modal.open) {
        return;
    }

    if (e.target === modal) {
        e.preventDefault();
        e.stopPropagation();
    }
}, true);


document.addEventListener('livewire:init', () => {
    Livewire.on('edit-user-modal', (event) => {
        const user = event.user;
        $('#edit_user_id').val(user.id ?? '');
        $('#edit_name').val(user.name ?? '');
        $('#edit_email').val(user.email ?? '');
        $('#edit_user_business_id').val(user.business_id ?? '');
        $('#edit_role').val(user.role ?? '');
        $('#edit_password').val('');
        if (window.Flux) {
            Flux.modal('edit-user-modal').show();
        }
    });

    Livewire.on('edit-business-modal', (event) => {
        const business = event.business;
        $('#edit_business_id').val(business.id ?? '');
        $('#edit_business_name').val(business.name ?? '');
        $('#edit_business_email').val(business.email ?? '');
        $('#edit_business_phone').val(business.phone ?? '');
        $('#edit_business_address').val(business.address ?? '');
        if (window.Flux) {
            Flux.modal('edit-business-modal').show();
        }
    });

});