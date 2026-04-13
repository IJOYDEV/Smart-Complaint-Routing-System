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


        <form action="controllers/registercontroller.php" method="POST">
            
        <?php if (isset($_GET['error'])): ?>
    <p style="color:red;">
        <?php
        if ($_GET['error'] === 'password_mismatch') echo "Passwords do not match.";
        if ($_GET['error'] === 'email_taken')        echo "Email already registered.";
        if ($_GET['error'] === 'register_failed')    echo "Registration failed. Try again.";
        ?>
    </p>
<?php endif; ?>

 <?php if (isset($_GET['success'])): ?>
        <p style="color:green;">
            <?php
            if ($_GET['success'] === 'registered') echo "Account created! Please login.";
            ?>
        </p>
    <?php endif; ?>
    
        <select name="role" required>
                <option value="">Select Role</option>
                <option value="citizen">Citizen</option>
            </select>

            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            
            <button type="submit" class="btn">Register</button>

            <p class="auth-switch">
                Already have an account?
                <a href="login.php">Login Here</a>
            </p>

        </form>
    </div>
</section>

</body>
</html>
