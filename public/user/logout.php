<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script defer src="signup.js"></script>
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <h2>Create Your Account</h2>
            <p>Join us and start your journey!</p>
            <form id="signupForm" method="POST" action="register.php">
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    <span class="input-error" id="nameError"></span>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    <span class="input-error" id="emailError"></span>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                    <span class="input-error" id="passwordError"></span>
                </div>
                <div class="input-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                    <span class="input-error" id="confirmPasswordError"></span>
                </div>
                <div class="actions">
                    <button type="submit" class="btn-primary">Sign Up</button>
                </div>
            </form>
            <div class="login-prompt">
                <p>Already have an account? <a href="index.html">Log in</a></p>
            </div>
        </div>
    </div>
</body>
</html>
