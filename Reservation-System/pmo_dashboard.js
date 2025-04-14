document.addEventListener('DOMContentLoaded', function() {
    loadEquipmentStatus();
    
    document.querySelector('.logout').addEventListener('click', function() {
        window.location.href = 'pmo_login.php';
    });
});

function editStatus(equipment) {
    // Convert equipment ID format (e.g., 'led-wall' to 'LED Wall')
    const equipmentName = equipment.split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    
    document.getElementById('currentEquipment').value = equipmentName;
    // Update modal options to remove 'Used' status
    const statusSelect = document.getElementById('equipmentStatus');
    statusSelect.innerHTML = `
        <option value="Available">Available</option>
        <option value="Maintenance">Maintenance</option>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

function updateStatus() {
    const equipment = document.getElementById('currentEquipment').value;
    const status = document.getElementById('equipmentStatus').value;
    
    fetch('update_equipment_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        // Fix the parameter name to match what the server expects
        body: `equipment=${encodeURIComponent(equipment)}&status=${encodeURIComponent(status)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Force a complete page reload to get fresh data
            window.location.reload();
        } else {
            alert('Failed to update status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
}

// Helper function to get badge class
function getBadgeClass(status) {
    switch(status) {
        case 'Available':
            return 'bg-success';
        case 'Used':
            return 'bg-warning';
        case 'Maintenance':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

function editStatus(equipment) {
    const equipmentName = equipment.split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    
    document.getElementById('currentEquipment').value = equipmentName;
    // Update modal options to remove 'Used' status
    const statusSelect = document.getElementById('equipmentStatus');
    statusSelect.innerHTML = `
        <option value="Available">Available</option>
        <option value="Maintenance">Maintenance</option>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

async function loadEquipmentStatus() {
    try {
        const response = await fetch('get_equipment_status.php');
        const data = await response.json();
        
        if (data.success) {
            data.equipment.forEach(item => {
                const equipmentId = item.name.toLowerCase().replace(/ /g, '-');
                const statusCell = document.getElementById(`${equipmentId}-status`);
                const updatedCell = document.getElementById(`${equipmentId}-updated`);
                
                if (statusCell && updatedCell) {
                    const badgeClass = getBadgeClass(item.status);
                    statusCell.innerHTML = `<span class="badge ${badgeClass}">${item.status}</span>`;
                    updatedCell.textContent = new Date(item.last_updated).toLocaleString();
                }
            });
        }
    } catch (error) {
        console.error('Error:', error);
    }
}