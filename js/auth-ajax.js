// auth-ajax.js
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const submitButton = form.querySelector('button[type="submit"]');
            const submitText = submitButton.querySelector('.btn-text');
            const spinner = submitButton.querySelector('.fa-spinner');
            
            // Show loading state
            submitText.textContent = 'Creating Account...';
            spinner.style.display = 'inline-block';
            submitButton.disabled = true;
            
            // Collect form data
            const formData = {
                firstName: document.getElementById('firstName').value.trim(),
                lastName: document.getElementById('lastName').value.trim(),
                email: document.getElementById('signupEmail').value.trim(),
                phoneNumber: document.getElementById('phoneNumber').value.trim(),
                password: document.getElementById('signupPassword').value,
                confirmPassword: document.getElementById('confirmPassword').value,
                agreeTerms: document.getElementById('agreeTerms').checked
            };
            
            // Send AJAX request
            fetch('/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message and redirect
                    alert(data.message);
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, message]) => {
                            const input = document.getElementById(field);
                            if (input) {
                                const formGroup = input.closest('.form-group') || input.closest('.form-row');
                                if (formGroup) {
                                    // Create or update error message
                                    let errorElement = formGroup.querySelector('.error-message');
                                    if (!errorElement) {
                                        errorElement = document.createElement('div');
                                        errorElement.className = 'error-message';
                                        formGroup.appendChild(errorElement);
                                    }
                                    errorElement.textContent = message;
                                    errorElement.style.color = 'red';
                                    errorElement.style.fontSize = '0.8rem';
                                    errorElement.style.marginTop = '5px';
                                }
                            }
                        });
                    } else {
                        alert(data.message || 'An error occurred during registration.');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'An error occurred during registration.');
            })
            .finally(() => {
                // Reset button state
                submitText.textContent = 'Create Account';
                spinner.style.display = 'none';
                submitButton.disabled = false;
            });
        });
    }
    
    // Clear error messages when user starts typing
    document.querySelectorAll('#signupForm input').forEach(input => {
        input.addEventListener('input', function() {
            const formGroup = this.closest('.form-group') || this.closest('.form-row');
            if (formGroup) {
                const errorElement = formGroup.querySelector('.error-message');
                if (errorElement) {
                    errorElement.remove();
                }
            }
        });
    });
});