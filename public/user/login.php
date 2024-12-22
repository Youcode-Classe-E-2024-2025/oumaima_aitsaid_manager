<?php
session_start();
require_once '../../config/database.php';

$error_message = '';
$success_message = '';

if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $error_message = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, id_role, is_approved, is_archifed FROM users WHERE email = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error_message = "No user found with this email.";
        } else {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                if ($user["is_approved"] == 0) {
                    $error_message = 'Your account is not approved by the admin. Please wait for approval.';
                } elseif ($user["is_archifed"] == 1) {
                    $error_message = 'Your account has been archived. Please contact the administrator.';
                } else {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["id_role"] = $user["id_role"];
                    $update_stmt = $conn->prepare("UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                    $update_stmt->bind_param("i", $user["id"]);
                    $update_stmt->execute();

                    switch ($user["id_role"]) {
                        case 1:
                            header("Location: ../admin/dashboard.php");
                            break;
                        case 2:
                            header("Location: ../chef/dashboard_chef.php");
                            break;
                        default:
                            header("Location: ../client/dashboard_client.php");
                    }
                    exit();
                }
            } else {
                $error_message = "Invalid password.";
            }
        }
        $stmt->close();
    }
}
if (isset($_SESSION["user_id"])) {
    header("Location: ../client/dashboard_client.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Site - Login & Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-4xl">
            <div class="flex justify-center mb-6 pt-8">
                <img alt="Restaurant logo with a chef hat and utensils" class="w-24 h-24" src="https://storage.googleapis.com/a1aa/image/EHNYpciCb54yOtM0fKFb1lK7ISIMku4rnQlrvtn21rqV6FeTA.jpg"/>
            </div>
            <div class="flex justify-center mb-6">
                <button id="login-tab" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-l focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <button id="register-tab" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:shadow-outline">
                    Register
                </button>
            </div>
            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-8" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mx-8" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($success_message); ?></span>
                </div>
            <?php endif; ?>
            <div id="login-section" class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login to Your Account</h2>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-email">Email</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="login-email" name="email" type="email" placeholder="Email" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="login-password">Password</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="login-password" name="password" type="password" placeholder="Password" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="login" type="submit">
                            Sign In
                        </button>
                        <a class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800" href="reset_password.php">
                            Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
            <div id="register-section" class="p-8 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Create a New Account</h2>
                <form id="signupForm" method="POST" action="../../modal/register_user.php">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-name">Name</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="register-name" name="name_user" type="text" placeholder="Name" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-email">Email</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="register-email" name="email_user" type="email" placeholder="Email" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-password">Password</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="register-password" name="password_user" type="password" placeholder="Password" required minlength="8">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="register-confirm-password">Confirm Password</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="register-confirm-password" name="confirmPassword_user" type="password" placeholder="Confirm Password" required minlength="8">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="register" type="submit">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('login-tab').addEventListener('click', function() {
            document.getElementById('login-section').classList.remove('hidden');
            document.getElementById('register-section').classList.add('hidden');
            this.classList.add('bg-red-700');
            document.getElementById('register-tab').classList.remove('bg-green-700');
        });

        document.getElementById('register-tab').addEventListener('click', function() {
            document.getElementById('register-section').classList.remove('hidden');
            document.getElementById('login-section').classList.add('hidden');
            this.classList.add('bg-green-700');
            document.getElementById('login-tab').classList.remove('bg-red-700');
        });
    </script>
</body>
</html>