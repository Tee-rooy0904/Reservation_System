<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Reservation System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="theme-switcher.css">
</head>
<body>
    <label class="theme-switch">
        <input class="slider" type="checkbox">
        <div class="switch">
            <div class="suns"></div>
            <div class="moons">
                <div class="star star-1"></div>
                <div class="star star-2"></div>
                <div class="star star-3"></div>
                <div class="star star-4"></div>
                <div class="star star-5"></div>
                <div class="first-moon"></div>
            </div>
            <div class="sand"></div>
            <div class="bb8">
                <div class="antennas">
                    <div class="antenna short"></div>
                    <div class="antenna long"></div>
                </div>
                <div class="head">
                    <div class="stripe one"></div>
                    <div class="stripe two"></div>
                    <div class="eyes">
                        <div class="eye one"></div>
                        <div class="eye two"></div>
                    </div>
                    <div class="stripe detail">
                        <div class="detail zero"></div>
                        <div class="detail zero"></div>
                        <div class="detail one"></div>
                        <div class="detail two"></div>
                        <div class="detail three"></div>
                        <div class="detail four"></div>
                        <div class="detail five"></div>
                        <div class="detail five"></div>
                    </div>
                    <div class="stripe three"></div>
                </div>
                <div class="ball">
                    <div class="lines one"></div>
                    <div class="lines two"></div>
                    <div class="ring one"></div>
                    <div class="ring two"></div>
                    <div class="ring three"></div>
                </div>
                <div class="shadow"></div>
            </div>
        </div>
    </label>

    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h1>Admin Login</h1>
                </div>
                <form id="adminLoginForm" autocomplete="on">
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
                    <a href="pmo_login.php" class="text-decoration-none">PMO? Log In</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="admin.js"></script>
    <script src="theme-switcher.js"></script>
</body>
</html>