document.addEventListener('click', function (e) {
    const modal = document.querySelector(
        'dialog[data-modal="add-user-modal"]'
    );

    if (!modal || !modal.open) {
        return;
    }

    if (e.target === modal) {
        e.preventDefault();
        e.stopPropagation();
    }
}, true);
