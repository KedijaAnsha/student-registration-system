document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('.validate-form');

    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const inputs = form.querySelectorAll('input[required], select[required]');

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'var(--danger)';
                    input.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                } else {
                    input.style.borderColor = 'var(--border)';
                    input.style.boxShadow = 'none';
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please fill out all required fields.');
            }
        });
    });
    
    // Password Toggle Logic
    const toggles = document.querySelectorAll('.toggle-password');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const targetId = this.getAttribute('data-target');
            const passwordField = document.getElementById(targetId);
            if (passwordField) {
                passwordField.type = this.checked ? 'text' : 'password';
            }
        });
    });
});

