document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('adminLoginForm');    
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('usernameError');

            // Clear any existing error messages
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }

            if (!username || !password) {
                if (errorMessage) {
                    errorMessage.style.display = 'block';
                    errorMessage.textContent = 'Please enter both username and password';
                }
                return;
            }

            try {
                const response = await fetch('login_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    sessionStorage.setItem('adminName', data.adminName);
                    sessionStorage.setItem('adminUser', username);
                    window.location.href = 'dashboard.php';
                } else {
                    errorMessage.style.display = 'block';
                    errorMessage.textContent = data.message || 'Invalid username or password';
                }
            } catch (error) {
                console.error('Login error:', error);
                errorMessage.textContent = 'An error occurred during login';
            }
        });
    }
});


// Update the sorting function
// Remove the duplicate sortReservations function and keep only this version
function sortReservations(reservations, field, order) {
    const sortedReservations = [...reservations];
    
    sortedReservations.sort((a, b) => {
        if (field === 'date') {
            const dateA = new Date(a.date);
            const dateB = new Date(b.date);
            return order === 'asc' ? dateA - dateB : dateB - dateA;
        } else if (field === 'name') {
            const nameA = (a.fullName || '').toLowerCase();
            const nameB = (b.fullName || '').toLowerCase();
            return order === 'asc' 
                ? nameA.localeCompare(nameB)
                : nameB.localeCompare(nameA);
        }
        return 0;
    });

    displayReservations(sortedReservations);
}

// Update the displayReservations function to remove the old sorting code
function displayReservations(reservations) {
    const tableBody = document.getElementById('reservationsTableBody');
    if (!tableBody) return;

    // Remove the old sorting code and table header click listeners
    tableBody.innerHTML = reservations.map(reservation => `
        <tr data-id="${reservation.idNo || ''}">
            // ... rest of the table row code ...
        </tr>
    `).join('');
    
    updateStats(reservations);
}

// Add the sort dropdown listener in initializeDashboard
function initializeDashboard() {
    document.getElementById('adminLoginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        try {
            const response = await fetch('login_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                sessionStorage.setItem('adminUser', username);
                window.location.href = 'dashboard.php';
            } else {
                document.getElementById('usernameError').textContent = data.message || 'Login failed';
            }
        } catch (error) {
            console.error('Login error:', error);
            document.getElementById('usernameError').textContent = 'An error occurred during login';
        }
    });
    // Add sort dropdown listener
    const sortSelect = document.getElementById('sortOption');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const reservations = JSON.parse(localStorage.getItem('reservations')) || [];
            
            switch(this.value) {
                case 'nameAsc':
                    sortReservations(reservations, 'name', 'asc');
                    break;
                case 'nameDesc':
                    sortReservations(reservations, 'name', 'desc');
                    break;
                case 'dateAsc':
                    sortReservations(reservations, 'date', 'asc');
                    break;
                case 'dateDesc':
                    sortReservations(reservations, 'date', 'desc');
                    break;
                default:
                    displayReservations(reservations);
            }
        });
    }
}