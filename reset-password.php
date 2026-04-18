<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Smart Complaint Routing System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<section class="login-section">
    <div class="login-card-container">
        <h2>Reset Password</h2>

        <?php
        $error = $_SESSION['reset_error'] ?? null;
        unset($_SESSION['reset_error']);

        $token = $_GET['token'] ?? '';

        if (empty($token)): ?>
            <p class="error-msg">Invalid or missing reset link.</p>
        <?php else: ?>

        <?php if ($error): ?>
            <p class="error-msg">
                <?php
                $messages = [
                    'password_mismatch' => 'Passwords do not match.',
                    'weak_password'     => 'Password must be at least 8 characters.',
                    'invalid_token'     => 'Reset link is invalid or has expired.',
                    'missing_fields'    => 'Please fill in all fields.',
                    'update_failed'     => 'Failed to update password. Try again.',
                ];
                echo htmlspecialchars($messages[$error] ?? 'An error occurred.');
                ?>
            </p>
        <?php endif; ?>

        <form action="controllers/resetpasswordcontroller.php" method="POST" novalidate>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="password-container">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="New Password"
                    required
                    minlength="8"
                >
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)"></button>
            </div>

            <div class="password-container">
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm New Password"
                    required
                >
                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)"></button>
            </div>

            <button type="submit" class="btn">Reset Password</button>
        </form>

        <?php endif; ?>

        <p class="auth-switch">
            <a href="login.php">Back to Login</a>
        </p>
    </div>
</section>

<script src="assets/js/login.js"></script>

</body>
</html>