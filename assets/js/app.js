document.addEventListener('DOMContentLoaded', function () {

    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const itemName = form.dataset.name || 'this record';

            const confirmed = confirm(
                'Are you sure you want to delete "' + itemName + '"?\n\n' +
                'This action cannot be undone.'
            );

            if (confirmed) {
                form.submit();
            }
        });
    });

    const alerts = document.querySelectorAll('.app-alert.auto-dismiss');

    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.4s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });


    const forms = document.querySelectorAll('.app-validate');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            let valid = true;

            // Check all required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(function (field) {
                // Remove previous error state
                field.classList.remove('is-invalid');
                const errorEl = field.parentNode.querySelector('.app-error-text');
                if (errorEl) errorEl.remove();

                // Validate empty
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');

                    // Append error message below field
                    const err = document.createElement('span');
                    err.className = 'app-error-text';
                    err.textContent = 'This field is required.';
                    field.parentNode.appendChild(err);
                }
            });

            if (!valid) {
                e.preventDefault(); // Block submission if invalid
            }
        });
    });

    const animatables = document.querySelectorAll('[data-animate]');
    animatables.forEach(function (el, i) {
        el.classList.add('fade-in-up', 'fade-in-up-' + (i + 1));
    });

});
