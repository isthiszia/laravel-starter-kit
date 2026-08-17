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