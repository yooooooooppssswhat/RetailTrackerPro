<?php
/**
 * Login Page - User authentication
 */
require_once __DIR__ . '/../includes/bootstrap.php';

if (is_logged_in()) {
    redirect('index.php?page=dashboard');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        flash('error', 'Please enter both username and password.');
    } else {
        if (login_user($username, $password)) {
            redirect('index.php?page=dashboard');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In | RetailTracker Pro</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="auth-page">
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="auth-logo"><i class="fa-solid fa-store"></i></div>
            <h1>RetailTracker Pro</h1>
            <p>Retail Management System</p>
        </div>

        <?php display_flash(); ?>

        <form method="post" class="auth-form">
            <div class="form-group">
                <label for="username"><i class="fa-solid fa-user"></i> Username</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username" />
            </div>
            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password" />
            </div>
            <button type="submit" class="button button-primary button-block">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In
            </button>
        </form>

        <div class="auth-footer">
            <p>&copy; <?= date('Y') ?> RetailTracker Pro</p>
        </div>
    </div>
</div>
</body>
</html>
