<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Smart Complaint Routing System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<section class="login-section">
    <div class="login-card-container">
        <h2>Forgot Password</h2>

        <?php
        $error   = $_SESSION['forgot_error']   ?? null;
        $success = $_SESSION['forgot_success'] ?? null;
        unset($_SESSION['forgot_error'], $_SESSION['forgot_success']);
        ?>

        <?php if ($error): ?>
            <p class="error-msg">
                <?php
                $messages = [
                    'email_not_found' => 'No account found with that email.',
                    'send_failed'     => 'Failed to send email. Try again.',
                    'missing_email'   => 'Please enter your email address.',
                    'invalid_email'   => 'Please enter a valid email address.',
                ];
                echo htmlspecialchars($messages[$error] ?? 'An error occurred.');
                ?>
            </p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="success-msg">Reset link sent! Check your email.</p>
        <?php endif; ?>

        <form action="controllers/forgotpasswordcontroller.php" method="POST" novalidate>
            <input
                type="email"
                name="email"
                placeholder="Enter your email address"
                required
            >
            <button type="submit" class="btn">Send Reset Link</button>
        </form>

        <p class="auth-switch">
            Remembered it? <a href="login.php">Login Here</a>
        </p>
    </div>
</section>

</body>
</html>