<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Smart Complaint Routing System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<section class="login-section">
    <div class="login-card-container">
        <h2>Create Account</h2>

        <?php
        $error   = $_SESSION['register_error']   ?? null;
        $success = $_SESSION['register_success'] ?? null;
        $old     = $_SESSION['register_old']     ?? [];

        unset($_SESSION['register_error'], $_SESSION['register_success'], $_SESSION['register_old']);
        ?>

        <?php if ($error): ?>
            <p class="error-msg">
                <?php
                $messages = [
                    'password_mismatch' => 'Passwords do not match.',
                    'email_taken'       => 'Email already registered.',
                    'register_failed'   => 'Registration failed. Please try again.',
                    'weak_password'     => 'Password must be at least 8 characters.',
                ];
                echo htmlspecialchars($messages[$error] ?? 'An error occurred.');
                ?>
            </p>
        <?php endif; ?>

        <?php if ($success === 'registered'): ?>
            <p class="success-msg">Account created! Please <a href="login.php">login here</a>.</p>
        <?php endif; ?>

        <form action="controllers/registercontroller.php" method="POST" novalidate>

            <select name="role" required>
                <option value="">Select Role</option>
                <option value="citizen" <?= ($old['role'] ?? '') === 'citizen' ? 'selected' : '' ?>>Citizen</option>
            </select>

            <input
                type="text"
                name="fullname"
                placeholder="Full Name"
                value="<?= htmlspecialchars($old['fullname'] ?? '') ?>"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                required
            >

            <div class="password-container">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required
                    minlength="8"
                    oninput="checkStrength(this.value)"
                >
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">Show</button>
            </div>
            <div class="strength-bar" id="strength-bar"></div>
            <p class="password-hint">Minimum 8 characters. Mix letters, numbers &amp; symbols for a strong password.</p>

            <div class="password-container">
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm Password"
                    required
                    oninput="checkMatch()"
                >
                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)">Show</button>
            </div>
            <p id="match-hint" class="password-hint"></p>

            <button type="submit" class="btn">Register</button>

            <p class="auth-switch">
                Already have an account? <a href="login.php">Login Here</a>
            </p>

        </form>
    </div>
</section>

<script src="assets/js/register.js"></script>

</body>
</html>