
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const password = document.getElementById('signupPassword').value;
    const confirm = document.getElementById('confirmPassword').value;
    
    // Validate password match
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match!');
        return;
    }
    
    // Validate password strength
    if (!/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(password)) {
        e.preventDefault();
        alert('Password must contain at least 8 characters with one uppercase, one lowercase, and one number');
        return;
    }
    
    // Show loading spinner
    this.querySelector('.fa-spinner').style.display = 'inline-block';
    this.querySelector('.btn-text').textContent = 'Creating Account...';
});
