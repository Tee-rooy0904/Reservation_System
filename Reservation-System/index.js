window.addEventListener('load', function() {
    const dateInput = document.getElementById('date_of_use');
    if (dateInput) {
        const today = new Date();
        const minDate = new Date(today);
        minDate.setDate(today.getDate() + 3); // This sets it to 3 days from today
        
        // Format the date properly for the input
        const formattedMinDate = minDate.toISOString().split('T')[0];
        
        // Set the minimum date and default value
        dateInput.setAttribute('min', formattedMinDate);
        dateInput.value = formattedMinDate;
        
        // Add validation for manual date entry
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const minAllowedDate = new Date(today);
            minAllowedDate.setDate(today.getDate() + 3);
            
            if (selectedDate < minAllowedDate) {
                alert('Bookings must be made at least 3 days in advance');
                this.value = formattedMinDate;
            }
        });
    }
    const form = document.getElementById('reservationForm');
    const submitButton = form.querySelector('button[type="submit"]');
    const idInput = document.getElementById('user_id_number'); // Changed from 'idNo'
    const roleSelect = document.getElementById('role');

    // Enable ID input if role is already selected
    if (roleSelect.value) {
        idInput.disabled = false;
        idInput.placeholder = roleSelect.value === 'personnel' ? 
            'Enter 3-digit ID number' : 
            'Enter 9 digit ID number';
    }

    roleSelect.addEventListener('change', function() {
        if (this.value) {
            idInput.disabled = false;
            idInput.value = '';
            idInput.placeholder = this.value === 'personnel' ? 
                'Enter 3-digit ID number' : 
                'Enter 9-10 digit ID number';
        } else {
            idInput.disabled = true;
            idInput.value = '';
        }
    });

    // Modified validateForm function with mobile support
    // Modified validateForm function to include equipment validation
    // Add event listeners for all form fields
    const checkAllFields = () => {
        const role = document.getElementById('role').value;
        const idNo = document.getElementById('user_id_number').value.trim();
        const fullName = document.getElementById('fullName').value.trim();
        const sex = document.getElementById('sex').value;
        const phone = document.getElementById('phone').value.trim();
        const date = document.getElementById('date_of_use').value;
        const time = document.getElementById('reservation_time').value;
        const event = document.getElementById('event_type').value;
        const venue = document.getElementById('venue').value;
        const selectedEquipments = document.querySelectorAll('input[name="equipment[]"]:checked');

        const isValidForm = role && role !== '--Please Select--' && 
            idNo && 
            fullName && 
            sex && sex !== '--Please Select--' && 
            phone && 
            date && 
            time && 
            event && event !== '--Please Select--' && 
            venue && venue !== '--Please Select--' && 
            selectedEquipments.length > 0;

        // Update button state
        if (submitButton) {
            submitButton.disabled = !isValidForm;
            submitButton.classList.toggle('btn-success', isValidForm);
            submitButton.classList.toggle('btn-danger', !isValidForm);
            submitButton.style.cursor = isValidForm ? 'pointer' : 'not-allowed';
        }
    };

    // Add event listeners for equipment checkboxes
    document.querySelectorAll('input[name="equipment[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', checkAllFields);
    });

    // Remove the duplicate load event listener and call checkAllFields directly
    checkAllFields();

    // Add this at the top of your file, outside any event listeners
    function validateForm() {
        const role = document.getElementById('role').value;
        const idNo = document.getElementById('user_id_number').value.trim();
        const fullName = document.getElementById('fullName').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const date = document.getElementById('date_of_use').value;
        const time = document.getElementById('reservation_time').value;
        const event = document.getElementById('event_type').value;
        const venue = document.getElementById('venue').value;
        const selectedEquipments = document.querySelectorAll('input[name="equipment[]"]:checked');
    
        // Validation logic
        const errors = [];
        if (!role || role === '--Please Select--') errors.push('Please select a role');
        if (!idNo) errors.push('Please enter your ID number');
        if (!fullName) errors.push('Please enter your full name');
        if (!phone) errors.push('Please enter your phone number');
        if (!date) errors.push('Please select a date');
        if (!time) errors.push('Please select a time');
        if (!event || event === '--Please Select--') errors.push('Please select an event type');
        if (!venue || venue === '--Please Select--') errors.push('Please select a venue');
        if (selectedEquipments.length === 0) errors.push('Please select at least one equipment');
    
        if (errors.length > 0) {
            alert(errors.join('\n'));
            return false;
        }
        return true;
    }

    // Add function to format date as DD/MM/YYYY
    function formatDate(date) {
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Set current date for reservation date field
    const reservationDateInput = document.getElementById('reservation_date');
    if (reservationDateInput) {
        const currentDate = new Date();
        reservationDateInput.value = formatDate(currentDate);
        // Make it read-only to prevent manual changes
        reservationDateInput.readOnly = true;
    }

    // Update reservation date display
    // Single source of truth for current date
    function getCurrentDate() {
        const now = new Date();
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Remove all other date update intervals and duplicate date handlers
    function updateReservationDate() {
        const reservationDateField = document.getElementById('reservation_date');
        if (reservationDateField) {
            reservationDateField.value = getCurrentDate();
            reservationDateField.readOnly = true;
        }
    }

    // Initial update and periodic refresh
    updateReservationDate();
    setInterval(updateReservationDate, 60000); // Update every minute

    // Update form submit handler
    // Add this function to check for existing reservations
    async function checkExistingReservation(userId) {
        try {
            const response = await fetch(`check_reservation.php?user_id=${encodeURIComponent(userId)}`);
            const data = await response.json();
            return data.hasActiveReservation;
        } catch (error) {
            console.error('Error checking reservation:', error);
            return false;
        }
    }

    // Keep only ONE form submit handler and update its logic
    window.addEventListener('load', function() {
        const form = document.getElementById('reservationForm');
        
        // Remove any existing listeners
        const newForm = form.cloneNode(true);
        form.parentNode.replaceChild(newForm, form);
        
        newForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }

            const userId = document.getElementById('user_id_number').value.trim();
            const hasActiveReservation = await checkExistingReservation(userId);

            if (hasActiveReservation) {
                alert('You cannot make a new reservation while you have an active or pending reservation.');
                // Disable form elements
                const formElements = this.elements;
                for (let element of formElements) {
                    element.disabled = true;
                }
                return;
            }
            
            const formData = new FormData(this);
            
            fetch('user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const scanOverlay = document.getElementById('scanOverlay');
                    if (scanOverlay) {
                        scanOverlay.style.display = 'flex';
                        updateBookingStatus('pending');
                        checkBookingStatus(userId);
                    }
                } else {
                    throw new Error(data.message || 'Booking failed');
                }
            })
            .catch(error => {
                console.error('Submission error:', error);
                alert('Error: ' + error.message);
            });
        });
    });

    // Update the status checking function
    async function checkBookingStatus(idNo) {
        try {
            const response = await fetch(`check_status.php?id=${encodeURIComponent(idNo)}`);
            const data = await response.json();
            
            if (data && data.status) {
                // Only update status if it's not empty
                if (data.status.trim() !== '') {
                    updateBookingStatus(data.status.toLowerCase());
                }
                
                // Continue checking if status is pending or empty
                if (data.status.toLowerCase() === 'pending' || data.status.trim() === '') {
                    setTimeout(() => checkBookingStatus(idNo), 2000);
                }
            } else {
                // If no status, show pending and continue checking
                updateBookingStatus('pending');
                setTimeout(() => checkBookingStatus(idNo), 2000);
            }
        } catch (error) {
            console.error('Error checking status:', error);
            // On error, maintain pending status and continue checking
            updateBookingStatus('pending');
            setTimeout(() => checkBookingStatus(idNo), 2000);
        }
    }

    // Update the form submit handler
    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        const formData = new FormData(this);
        const now = new Date();
        const currentDate = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()}`;
        formData.set('reservation_date', currentDate);
        
        formData.append('date_of_use', document.getElementById('date_of_use').value);
        formData.append('fullName', document.getElementById('fullName').value);
        
        fetch('user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Server response:', text);
                throw new Error('Invalid server response');
            }
        })
        .then(data => {
            if (data.success) {
                const scanOverlay = document.getElementById('scanOverlay');
                if (scanOverlay) {
                    scanOverlay.style.display = 'flex';
                    updateBookingStatus('pending');
                    const idNo = document.getElementById('user_id_number').value.trim();
                    checkBookingStatus(idNo);
                }
            } else {
                // Show a more user-friendly message for active reservations
                if (data.message === 'You already have an active reservation') {
                    alert('You cannot make a new reservation while you have an active or pending reservation.');
                } else {
                    throw new Error(data.message || 'Booking failed');
                }
            }
        })
        .catch(error => {
            console.error('Submission error:', error);
            alert('Error: ' + error.message);
        });
    });
});


function checkEquipmentAvailability(date, time) {
    fetch('get_reservations.php')
        .then(response => response.json())
        .then(data => {
            const reservations = data.reservations || [];
            const unavailableEquipments = new Set();
            
            const selectedDateTime = new Date(`${date} ${time}`);
            const selectedTime = selectedDateTime.getTime();
            
            reservations.forEach(reservation => {
                if (reservation.status === 'pending' || reservation.status === 'approved') {
                    const reservationDateTime = new Date(`${reservation.date} ${reservation.time}`);
                    const reservationTime = reservationDateTime.getTime();
                    
                    // Check if it's exactly the same date and time
                    if (date === reservation.date && time === reservation.time) {
                        const equipments = reservation.equipment ? reservation.equipment.split(',') : [];
                        equipments.forEach(eq => unavailableEquipments.add(eq.trim()));
                    } else {
                        // Also check for 3-hour window
                        const timeDiff = Math.abs(selectedTime - reservationTime) / (1000 * 60 * 60);
                        if (timeDiff <= 3) {
                            const equipments = reservation.equipment ? reservation.equipment.split(',') : [];
                            equipments.forEach(eq => unavailableEquipments.add(eq.trim()));
                        }
                    }
                }
            });
            
            updateEquipmentUI(unavailableEquipments);
        })
        .catch(error => {
            console.error('Error checking equipment availability:', error);
        });
}

function updateEquipmentUI(unavailableEquipments) {
    const checkboxes = document.querySelectorAll('input[name="equipment[]"]');
    
    checkboxes.forEach(checkbox => {
        if (unavailableEquipments.has(checkbox.value)) {
            checkbox.disabled = true;
            checkbox.checked = false;
            
            let alertSpan = checkbox.nextElementSibling.nextElementSibling;
            if (!alertSpan || !alertSpan.classList.contains('text-danger')) {
                alertSpan = document.createElement('span');
                alertSpan.className = 'text-danger ml-2';
                alertSpan.style.fontSize = '12px';
                checkbox.parentNode.appendChild(alertSpan);
            }
            alertSpan.textContent = '(Not available - Already booked)';
        } else {
            checkbox.disabled = false;
            const alertSpan = checkbox.nextElementSibling.nextElementSibling;
            if (alertSpan && alertSpan.classList.contains('text-danger')) {
                alertSpan.remove();
            }
        }
    });
}

function updateBookingStatus(status) {
    const statusElement = document.getElementById('bookingStatus');
    const pendingContainer = document.getElementById('pendingStatus');
    const approvedContainer = document.getElementById('approvedStatus');
    const rejectedContainer = document.getElementById('rejectedStatus');
    
    // Hide all containers first
    [pendingContainer, approvedContainer, rejectedContainer].forEach(container => {
        if (container) {
            container.style.display = 'none';
        }
    });
    
    // Show the appropriate container based on status
    switch(status.toLowerCase()) {
        case 'pending':
            if (pendingContainer) pendingContainer.style.display = 'flex';
            break;
        case 'approved':
            if (approvedContainer) approvedContainer.style.display = 'flex';
            break;
        case 'rejected':
            if (rejectedContainer) rejectedContainer.style.display = 'flex';
            break;
    }
    
    // Make sure the overlay is visible
    const scanOverlay = document.getElementById('scanOverlay');
    if (scanOverlay) {
        scanOverlay.style.display = 'flex';
    }
}

// Update form submit handler
document.getElementById('reservationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const scanOverlay = document.getElementById('scanOverlay');
            if (scanOverlay) {
                scanOverlay.style.display = 'flex';
                updateBookingStatus('pending');
                const idNo = document.getElementById('user_id_number').value.trim();
                checkBookingStatus(idNo);
            }
        } else {
            throw new Error(data.message || 'Booking failed');
        }
    })
    .catch(error => {
        console.error('Submission error:', error);
        alert('Error: ' + error.message);
    });
});

    // Update the date input restrictions
    const dateOfUseInput = document.getElementById('date_of_use');
    const today = new Date();
        
        // Set minimum date to tomorrow
        const minDate = new Date(today);
        minDate.setDate(today.getDate() + 1);
        const formattedMinDate = minDate.toISOString().split('T')[0];
            
        dateOfUseInput.setAttribute('min', formattedMinDate);
    
        // Prevent manual date entry and enforce minimum date
        dateOfUseInput.addEventListener('input', function(e) {
            const selectedDate = new Date(this.value);
            const tomorrow = new Date();
            tomorrow.setDate(today.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);
            
            if (selectedDate < tomorrow) {
                alert('Bookings must be made at least 1 day in advance');
                this.value = formattedMinDate;
            }
        });
    
        // Update form submit handler to send the correct date
        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            const formData = new FormData(this);
            // Use date_of_use instead of current date
            formData.append('date', document.getElementById('date_of_use').value);
            formData.append('fullName', document.getElementById('fullName').value);
            
            fetch('user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Server response:', text);
                    throw new Error('Invalid server response');
                }
            })
            .then(data => {
                if (data.success) {
                    const scanOverlay = document.getElementById('scanOverlay');
                    if (scanOverlay) {
                        scanOverlay.style.display = 'flex';
                        updateBookingStatus('pending');
                        // Fix: Change idNo to user_id_number
                        const idNo = document.getElementById('user_id_number').value.trim();
                        checkBookingStatus(idNo);
                    }
                } else {
                    throw new Error(data.message || 'Booking failed');
                }
            })
            .catch(error => {
                console.error('Submission error:', error);
                alert('Error: ' + error.message);
            });
        });

        // Close overlay button functionality
        document.querySelector('.close-btn').addEventListener('click', function() {
            document.getElementById('scanOverlay').style.display = 'none';
        });

        // Add status checking function
        // Update the status checking function
        async function checkBookingStatus(idNo) {
            try {
                const response = await fetch(`check_status.php?id=${encodeURIComponent(idNo)}`);
                const data = await response.json();
                console.log('Raw status response:', data); // Debug log
                
                // Check if data exists and has a status property
                if (data && data.status) {
                    console.log('Current status:', data.status); // Debug log
                    updateBookingStatus(data.status);
                    
                    // Continue checking only if status is pending
                    if (data.status === 'pending') {
                        setTimeout(() => checkBookingStatus(idNo), 2000);
                    }
                } else {
                    console.log('No status data received:', data); // Debug log
                    // Continue checking if no valid status received
                    setTimeout(() => checkBookingStatus(idNo), 2000);
                }
            } catch (error) {
                console.error('Error checking status:', error);
                setTimeout(() => checkBookingStatus(idNo), 2000);
            }
        }

        // Update close button handler
        // Define closeButton properly
        const closeButton = document.querySelector('.close-btn');
        
        // Single close button handler
        if (closeButton) {
            closeButton.addEventListener('click', function(e) {
                e.preventDefault();
                const overlay = document.getElementById('scanOverlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
                location.reload();
            });
        }


// Update the checkEquipmentAvailability function
function checkEquipmentAvailability(date, time) {
    fetch(`check_equipment.php?date=${encodeURIComponent(date)}&time=${encodeURIComponent(time)}`)
        .then(response => response.json())
        .then(data => {
            const unavailableEquipments = new Set(data.booked_equipment || []);
            updateEquipmentUI(unavailableEquipments);
        })
        .catch(error => {
            console.error('Error checking equipment availability:', error);
        });
}

// Add event listeners for date and time inputs
document.getElementById('date_of_use').addEventListener('change', function() {
    const time = document.getElementById('reservation_time').value;
    if (time) {
        checkEquipmentAvailability(this.value, time);
    }
});

document.getElementById('reservation_time').addEventListener('change', function() {
    const date = document.getElementById('date_of_use').value;
    if (date) {
        checkEquipmentAvailability(date, this.value);
    }
});