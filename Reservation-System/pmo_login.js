document.getElementById('pmoLoginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorDiv = document.getElementById('usernameError');
    
    try {
        const response = await fetch('pmo_login_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        });
        
        const data = await response.json();
        console.log('Response:', data); // Debug log
        
        if (data.success) {
            console.log('Login successful, redirecting...'); // Debug log
            // Force a direct page load
            document.location = 'pmo_dashboard.php';
            return false;
        } else {
            errorDiv.textContent = data.message || 'Invalid username or password';
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        console.error('Login error:', error);
        errorDiv.textContent = 'An error occurred during login';
        errorDiv.style.display = 'block';
    }
});