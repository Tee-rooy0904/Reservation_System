<?php
session_start();
if (!isset($_SESSION['pmo_user'])) { // Changed from pmo_logged_in to pmo_user
    header('Location: pmo_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMO Dashboard - Equipment Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PMO Dashboard</a>
            <div class="d-flex align-items-center">
                
                <button class="btn btn-outline-danger btn-sm logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Equipment Status Management</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>LED Wall</td>
                                <td id="led-wall-status"><span class="badge bg-success">Available</span></td>
                                <td id="led-wall-updated">Now</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editStatus('led-wall')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Live Streaming Camera</td>
                                <td id="live-streaming-camera-status"><span class="badge bg-success">Available</span></td>
                                <td id="live-streaming-camera-updated">Now</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editStatus('live-streaming-camera')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>TV</td>
                                <td id="tv-status"><span class="badge bg-success">Available</span></td>
                                <td id="tv-updated">Now</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editStatus('tv')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Tripad</td>
                                <td id="tripad-status"><span class="badge bg-success">Available</span></td>
                                <td id="tripad-updated">Now</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editStatus('tripad')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>HDMI</td>
                                <td id="hdmi-status"><span class="badge bg-success">Available</span></td>
                                <td id="hdmi-updated">Now</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editStatus('hdmi')">Edit</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Lights</td>
                                <td id="lights-status"><span class="badge bg-success">Available</span></td>
                                <td id="lights-updated">Now</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editStatus('lights')">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Edit Modal -->
    <div class="modal fade" id="statusModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Equipment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="currentEquipment">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="equipmentStatus">
                            <option value="Available">Available</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateStatus()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="pmo_dashboard.js"></script>
</body>
</html>