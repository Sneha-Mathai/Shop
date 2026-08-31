// Handles the "are you sure?" confirmation for any delete form on the page.
// Blade files just add class="js-confirm-delete" and a data-confirm-message
// attribute — no inline JavaScript in the markup.
document.addEventListener('submit', function (event) {
    const form = event.target;

    if (!form.classList.contains('js-confirm-delete')) {
        return;
    }

    const message = form.dataset.confirmMessage || 'Are you sure?';

    if (!window.confirm(message)) {
        event.preventDefault();
    }
});