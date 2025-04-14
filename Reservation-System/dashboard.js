// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add logout button handler
    const logoutBtn = document.querySelector('.logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            window.location.href = 'admin.php'; // Changed to correct path within admin-panel
        });
    }

    // Fix search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }
    const sortOption = document.getElementById('sortOption');
    const statusFilter = document.getElementById('statusFilter');
    const refreshBtn = document.querySelector('.refresh-btn');

    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }
    if (sortOption) {
        sortOption.addEventListener('change', handleSort);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', handleStatusFilter);
    }
    if (refreshBtn) {
        refreshBtn.addEventListener('click', fetchReservations);
    }

    // Initial data fetch
    fetchReservations();
});

function handleSearch(event) {
    const searchValue = event.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#reservationsTableBody tr');
    
    rows.forEach(row => {
        const textContent = Array.from(row.querySelectorAll('td')).slice(0, -1) // Exclude actions column
            .map(cell => cell.textContent.toLowerCase())
            .join(' ');
        row.style.display = textContent.includes(searchValue) ? '' : 'none';
    });
}

function handleSort() {
    const sortBy = this.value;
    const tbody = document.getElementById('reservationsTableBody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aValue, bValue;
        
        switch(sortBy) {
            case 'nameAsc':
                aValue = a.querySelector('td:nth-child(2)').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(2)').textContent.toLowerCase();
                return aValue.localeCompare(bValue);
            case 'nameDesc':
                aValue = a.querySelector('td:nth-child(2)').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(2)').textContent.toLowerCase();
                return bValue.localeCompare(aValue);
            case 'dateAsc':
                // Use booking time column (12th column) instead of date of use
                aValue = new Date(a.querySelector('td:nth-child(12)').textContent);
                bValue = new Date(b.querySelector('td:nth-child(12)').textContent);
                return aValue - bValue;
            case 'dateDesc':
                // Use booking time column (12th column) instead of date of use
                aValue = new Date(a.querySelector('td:nth-child(12)').textContent);
                bValue = new Date(b.querySelector('td:nth-child(12)').textContent);
                return bValue - aValue;
            default:
                return 0;
        }
    });
    
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
}

function handleStatusFilter() {
    const filterValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#reservationsTableBody tr');
    
    rows.forEach(row => {
        const statusCell = row.querySelector('td[data-status]');
        if (statusCell) {
            const status = statusCell.getAttribute('data-status').toLowerCase();
            row.style.display = (filterValue === 'all status' || status === filterValue) ? '' : 'none';
        }
    });
}

// Keep only one copy of these functions
function approveReservation(id) {
    updateStatus(id, 'approved');
}

function rejectReservation(id) {
    updateStatus(id, 'rejected');
}

function deleteReservation(id) {
    if (confirm('Are you sure you want to delete this reservation?')) {
        const formData = new FormData();
        formData.append('reservation_id', id);

        fetch('delete_reservation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row immediately without full refresh
                const row = document.querySelector(`tr[data-id="${id}"]`);
                row.remove();
                updateStatistics();
            } else {
                throw new Error(data.message || 'Failed to delete reservation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting reservation: ' + error.message);
        });
    }
}

function updateStatus(id, status) {
    const formData = new FormData();
    formData.append('reservation_id', id);
    formData.append('status', status);

    fetch('update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status immediately in the UI
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const statusCell = row.querySelector('td[data-status]');
            const statusBadge = statusCell.querySelector('.badge');
            
            // Update status badge
            statusCell.setAttribute('data-status', status);
            statusBadge.className = `badge bg-${status === 'approved' ? 'success' : 'danger'}`;
            statusBadge.textContent = status.toUpperCase();
            
            // Update button states
            const approveBtn = row.querySelector('.btn-success');
            const rejectBtn = row.querySelector('.btn-danger');
            
            approveBtn.disabled = status === 'approved';
            rejectBtn.disabled = status === 'rejected';
            
            // Update statistics without full refresh
            updateStatistics();
        } else {
            throw new Error(data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status: ' + error.message);
    });
}

function deleteReservation(id) {
    if (confirm('Are you sure you want to delete this reservation?')) {
        const formData = new FormData();
        formData.append('reservation_id', id);

        fetch('delete_reservation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row immediately without full refresh
                const row = document.querySelector(`tr[data-id="${id}"]`);
                row.remove();
                updateStatistics();
            } else {
                throw new Error(data.message || 'Failed to delete reservation');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting reservation: ' + error.message);
        });
    }
}

function sendSMS(phone, message) {
    const formData = new FormData();
    formData.append('phone', phone);
    formData.append('message', message);

    fetch('send_sms.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('SMS sent successfully');
        } else {
            throw new Error(data.message || 'Failed to send SMS');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending SMS: ' + error.message);
    });
}

// Add this new function to update statistics
function updateStatistics() {
    fetch('get_statistics.php')
        .then(response => response.json())
        .then(data => {
            document.querySelector('#totalReservations .number').textContent = data.total;
            document.querySelector('#pendingReservations .number').textContent = data.pending;
            document.querySelector('#activeReservations .number').textContent = data.approved;
        })
        .catch(error => console.error('Error updating statistics:', error));
}

function fetchReservations() {
    fetch('get_reservations.php')
        .then(response => response.json())
        .then(data => {
            updateDashboard(data);
        })
        .catch(error => {
            console.error('Error fetching reservations:', error);
        });
}

function updateDashboard(data) {
    // Update statistics
    document.querySelector('#totalReservations .number').textContent = data.stats.total;
    document.querySelector('#pendingReservations .number').textContent = data.stats.pending;
    document.querySelector('#activeReservations .number').textContent = data.stats.approved;

    // Update table
    const tbody = document.getElementById('reservationsTableBody');
    tbody.innerHTML = '';

    data.reservations.forEach(res => {
        const row = document.createElement('tr');
        row.setAttribute('data-id', res.id);
        
        const statusClass = res.status === 'pending' ? 'warning' : 
                          res.status === 'approved' ? 'success' : 'danger';
        
        row.innerHTML = `
            <td>${res.user_id_number}</td>
            <td>${res.fullName || ''}</td>
            <td>${res.role || ''}</td>
            <td>${res.sex || ''}</td>
            <td>${res.phone || ''}</td>
            <td>${res.date_of_use}</td>
            <td>${res.reservation_time}</td>
            <td>${res.event_type}</td>
            <td>${res.venue}</td>
            <td>${res.equipment}</td>
            <td data-status="${res.status}">
                <span class="badge bg-${statusClass}">
                    ${res.status.toUpperCase()}
                </span>
            </td>
            <td>${res.booking_timestamp}</td>
            <td>
                <div class="btn-group">
                    ${res.status === 'pending' ? `
                        <button onclick="approveReservation('${res.id}')" 
                                class="btn btn-sm btn-success">
                            <i class="fas fa-check"></i>
                        </button>
                        <button onclick="rejectReservation('${res.id}')" 
                                class="btn btn-sm btn-danger">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                    <button onclick="deleteReservation('${res.id}')" 
                            class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button onclick="sendSMS('${res.phone}', '${res.status}')" 
                            class="btn btn-sm btn-primary"
                            ${!res.phone ? 'disabled' : ''}>
                        <i class="fas fa-comment"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Auto-refresh every 30 seconds
setInterval(fetchReservations, 30000);


function handleRefresh() {
    fetchReservations();
    const searchInput = document.getElementById('searchInput');
    const sortOption = document.getElementById('sortOption');
    const statusFilter = document.getElementById('statusFilter');
    
    // Reset all filters
    if (searchInput) searchInput.value = '';
    if (sortOption) sortOption.value = '';
    if (statusFilter) statusFilter.value = 'All Status';
}