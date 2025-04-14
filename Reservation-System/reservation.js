// Add this at the beginning of your file
document.addEventListener('DOMContentLoaded', function() {
    checkEquipmentStatus();
    setInterval(checkEquipmentStatus, 60000);

    // Add event listeners for date and time changes
    document.getElementById('date_of_use').addEventListener('change', checkEquipmentStatus);
    document.getElementById('reservation_time').addEventListener('change', checkEquipmentStatus);
});

function checkEquipmentStatus() {
    const selectedDate = document.getElementById('date_of_use').value;
    const selectedTime = document.getElementById('reservation_time').value;

    if (!selectedDate || !selectedTime) return;

    fetch('check_equipment_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `date=${encodeURIComponent(selectedDate)}&time=${encodeURIComponent(selectedTime)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            data.equipment.forEach(item => {
                const checkbox = document.querySelector(`input[type="checkbox"][value="${item.name}"]`);
                if (checkbox) {
                    if (item.status === 'Maintenance' || item.status === 'Used' || item.timeConflict) {
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        checkbox.parentElement.style.opacity = '0.5';
                        
                        const status = item.timeConflict ? 'Reserved' : item.status;
                        checkbox.parentElement.title = `Equipment ${status.toLowerCase()}`;
                        
                        const statusBadge = document.createElement('span');
                        statusBadge.className = `badge ${getStatusBadgeClass(status)} ms-2`;
                        statusBadge.textContent = status;
                        
                        const existingBadge = checkbox.parentElement.querySelector('.badge');
                        if (existingBadge) {
                            existingBadge.remove();
                        }
                        checkbox.parentElement.appendChild(statusBadge);
                    } else {
                        checkbox.disabled = false;
                        checkbox.parentElement.style.opacity = '1';
                        checkbox.parentElement.title = '';
                        
                        const existingBadge = checkbox.parentElement.querySelector('.badge');
                        if (existingBadge) {
                            existingBadge.remove();
                        }
                    }
                }
            });
        }
    })
    .catch(error => console.error('Error:', error));
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'Maintenance':
            return 'bg-danger';
        case 'Used':
        case 'Reserved':
            return 'bg-warning';
        default:
            return 'bg-success';
    }
}