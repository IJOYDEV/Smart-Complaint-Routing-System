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

        <form action="controllers/logincontroller.php" method="POST">

            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
           
            <button type="submit" class="btn">Login</button>
            <p class="auth-switch">
    Don’t have an account?
    <a href="register.php">Create Account</a>
</p>

    <?php if (isset($_GET['error'])): ?>
    <p style="color:red;">
        <?php 
        if ($_GET['error'] === 'invalid_password') echo "Wrong password. Try again.";
        if ($_GET['error'] === 'user_not_found')   echo "No account found with that email.";
        ?>
    </p>
<?php endif; ?>
</form>
    </div>
</section>

</body>
</html>
