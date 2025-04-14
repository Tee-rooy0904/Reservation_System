
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-danger btn-sm logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card" id="totalReservations">
                    <div class="card-body">
                        <h5 class="card-title">Total Reservations</h5>
                        <p class="number">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" id="pendingReservations">
                    <div class="card-body">
                        <h5 class="card-title">Pending Reservations</h5>
                        <p class="number">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" id="activeReservations">
                    <div class="card-body">
                        <h5 class="card-title">Active Reservations</h5>
                        <p class="number">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    <button class="btn btn-outline-secondary refresh-btn" type="button">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="sortOption">
                    <option value="">Sort By</option>
                    <option value="nameAsc">Name (A-Z)</option>
                    <option value="nameDesc">Name (Z-A)</option>
                    <option value="dateAsc">Booking Time (Oldest)</option>
                    <option value="dateDesc">Booking Time (Newest)</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="statusFilter">
                    <option value="all status">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <!-- Reservations Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Sex</th>
                                <th>Phone</th>
                                <th>Date of Use</th>
                                <th>Time</th>
                                <th>Event Type</th>
                                <th>Venue</th>
                                <th>Equipment</th>
                                <th>Status</th>
                                <th>Booking Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reservationsTableBody">
                            <!-- Data will be loaded dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SMS Modal -->
    <div class="modal fade" id="smsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send SMS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Recipient</label>
                        <input type="text" class="form-control" id="smsRecipient" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" id="smsMessage" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="sendSmsBtn">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dashboard.js"></script>
</body>
</html>
