<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script defer src="script.js"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Welcome Back!</h2>
            <p>Login to access your account</p>
            <form id="loginForm" method="POST" action="login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    <span class="input-error" id="emailError"></span>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <span class="input-error" id="passwordError"></span>
                </div>
                <div class="actions">
                    <button type="submit" class="btn-primary">Login</button>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
            </form>
            <div class="signup-prompt">
                <p>Don't have an account? <a href="register.html">Sign up</a></p>
            </div>
        </div>
    </div>
</body>
</html>

    
</head>
<body>