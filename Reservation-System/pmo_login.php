<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMO Login - Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h1>PMO Login</h1>
                </div>
                <form id="pmoLoginForm" autocomplete="on">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" required autocomplete="username">
                        <div id="usernameError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" required autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn-primary">Log in</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="admin.php" class="text-decoration-none">Admin? Log In</a>
                </div>
            </div>
        </div>
    </div>
    <script src="pmo_login.js"></script>
</body>
</html>