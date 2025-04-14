<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation System Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <header class="text-center mb-4">
        <h4 style="font-weight: lighter; font-family:Georgia, 'Times New Roman', Times, serif">
            <img src="smcc-logo.jpg" alt="#" style="height: 40px; width: 40px;"> SMCC Reservation
        </h4>
    </header>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body bg-primary">
                        <form id="reservationForm" action="user.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 form-group text-light">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role">
                                        <option value="">--Please Select--</option>
                                        <option value="student">Student</option>
                                        <option value="personnel">Personnel</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group text-light">
                                    <label for="user_id_number">ID Number</label>
                                    <input type="tel" class="form-control" id="user_id_number" name="user_id_number" placeholder="Enter your ID number">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group text-light">
                                    <label for="fullName">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name">
                                </div>
                                <div class="col-md-6 form-group text-light">
                                    <label for="sex">Sex</label>
                                    <select class="form-control" id="sex" name="sex" required>
                                        <option>--Please Select--</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group text-light">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="09123456789" pattern="^09\d{9}$" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group text-light">
                                    <label for="dateOfUse">Date of Use</label>
                                    <input type="date" class="form-control" id="date_of_use" name="date_of_use" required>
                                </div>
                                <div class="col-md-6 form-group text-light">
                                    <label for="time">Time of Use</label>
                                    <input type="time" class="form-control" id="reservation_time" name="reservation_time" required>
                                </div>
                            </div>
                            <!-- Remove extra closing div here that was breaking the form -->
                            
                            <div class="row">
                                <div class="col-md-6 form-group text-light">
                                    <label for="event_type">Event</label>
                                    <select class="form-control" id="event_type" name="event_type">
                                        <option value="">--Please Select--</option>
                                        <option value="workshop">Workshop</option>
                                        <option value="symposium">Symposium</option>
                                        <option value="seminar">Seminar</option>
                                        <option value="conference">Conference</option>
                                        <option value="foundation">Foundation Day</option>
                                        <option value="party">Party</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group text-light">
                                    <label for="venue">Venue</label>
                                    <select class="form-control" id="venue" name="venue">
                                        <option value="">--Please Select--</option>
                                        <option value="AVR">AVR</option>
                                        <option value="Quadrangle">Quadrangle</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="equipments" style="color: white;">Equipments</label>
                                    <div class="equipments-container bg-white p-3 rounded">
                                        <div class="equipment-item">
                                            <input type="checkbox" id="eq1" name="equipment[]" value="LED Wall">
                                            <label for="eq1">LED Wall</label>
                                        </div>
                                        <div class="equipment-item">
                                            <input type="checkbox" id="eq2" name="equipment[]" value="Live Streaming Camera">
                                            <label for="eq2">Live Streaming Camera</label>
                                        </div>
                                        <div class="equipment-item">
                                            <input type="checkbox" id="eq3" name="equipment[]" value="TV">
                                            <label for="eq3">TV</label>
                                        </div>
                                        <div class="equipment-item">
                                            <input type="checkbox" id="eq4" name="equipment[]" value="Tripad">
                                            <label for="eq4">Tripad</label>
                                        </div>
                                        <div class="equipment-item">
                                            <input type="checkbox" id="eq5" name="equipment[]" value="HDMI">
                                            <label for="eq5">HDMI</label>
                                        </div>
                                        <div class="equipment-item">
                                            <input type="checkbox" id="eq6" name="equipment[]" value="Lights">
                                            <label for="eq6">Lights</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block mt-3">Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this before closing body tag -->
    <div id="scanOverlay" class="scan-overlay">
        <div class="scan-content">
            <button class="close-btn">&times;</button>
            <h2>Booking Status</h2>
            <!-- Remove or hide the badge -->
            <div id="bookingStatus" class="badge mb-3" style="display: none;"></div>
            
            <div id="pendingStatus" class="status-container">
                <div class="loading-spinner"></div>
                <p class="status-text">Please wait while we process your booking...</p>
            </div>
            
            <div id="approvedStatus" class="status-container" style="display: none;">
                <img src="approved.png" alt="Approved" class="status-icon">
                <p class="status-text">Your booking has been approved!</p>
            </div>
            
            <div id="rejectedStatus" class="status-container" style="display: none;">
                <img src="rejected.png" alt="Rejected" class="status-icon">
                <p class="status-text">Your booking has been rejected!</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="index.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        updateEquipmentStatus();
        // Check status more frequently (every 5 seconds)
        setInterval(updateEquipmentStatus, 5000);
    });
    
    function updateEquipmentStatus() {
        fetch('get_equipment_status.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.equipment.forEach(item => {
                        const checkbox = document.querySelector(`input[type="checkbox"][value="${item.name}"]`);
                        if (checkbox) {
                            const label = checkbox.parentElement;
                            
                            // Handle different status types
                            switch(item.status) {
                                case 'Maintenance':
                                case 'Used':
                                    checkbox.disabled = true;
                                    checkbox.checked = false;
                                    label.style.opacity = '0.5';
                                    label.style.cursor = 'not-allowed';
                                    label.style.backgroundColor = '#f8d7da';
                                    label.title = `Equipment ${item.status.toLowerCase()}`;
                                    // Add status indicator
                                    let statusText = label.querySelector('.status-indicator');
                                    if (!statusText) {
                                        statusText = document.createElement('span');
                                        statusText.className = 'status-indicator ml-2 badge';
                                        label.appendChild(statusText);
                                    }
                                    statusText.textContent = item.status;
                                    statusText.style.backgroundColor = item.status === 'Maintenance' ? '#dc3545' : '#ffc107';
                                    statusText.style.color = item.status === 'Maintenance' ? 'white' : 'black';
                                    break;
                                    
                                case 'Available':
                                    checkbox.disabled = false;
                                    checkbox.parentElement.style.opacity = '1';
                                    checkbox.parentElement.style.cursor = 'pointer';
                                    checkbox.parentElement.style.backgroundColor = '';
                                    checkbox.parentElement.title = 'Available for booking';
                                    // Remove status indicator if exists
                                    const existingStatus = label.querySelector('.status-indicator');
                                    if (existingStatus) {
                                        existingStatus.remove();
                                    }
                                    break;
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }
    </script>
    <style>
    .equipment-item {
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
    }
    
    .status-indicator {
        font-size: 0.8em;
        padding: 2px 6px;
        border-radius: 3px;
        margin-left: 8px;
    }
    
    .equipment-item:hover {
        background-color: #f8f9fa;
    }
    
    .equipment-item[title] {
        position: relative;
    }
    </style>
</body>
</html>