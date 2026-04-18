<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Smart Complaint Routing System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<section class="login-section">
    <div class="login-card-container">
        <h2>System Login</h2>

        <?php
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        ?>

        <?php if ($error): ?>
            <p class="error-msg">
                <?php
                $messages = [
                    'invalid_password' => 'Wrong password. Try again.',
                    'user_not_found'   => 'No account found with that email.',
                    'login_failed'     => 'Login failed. Please try again.',
                ];
                echo htmlspecialchars($messages[$error] ?? 'An error occurred.');
                ?>
            </p>
        <?php endif; ?>

        <form action="controllers/logincontroller.php" method="POST" novalidate>

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                value="<?= htmlspecialchars($_SESSION['login_old_email'] ?? '') ?>"
                required
            >

            <div class="password-container">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required
                >
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)"></button>
            </div>

            <p class="auth-switch" style="margin-top: -10px; margin-bottom: 15px;">
                <a href="forgot-password.php">Forgot Password?</a>
            </p>

            <button type="submit" class="btn">Login</button>

            <p class="auth-switch">
                Don't have an account? <a href="register.php">Create Account</a>
            </p>

        </form>
    </div>
</section>

<script src="assets/js/login.js"></script>

</body>
</html>