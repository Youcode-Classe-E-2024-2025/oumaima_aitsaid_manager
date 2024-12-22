<?php
session_start();
require_once('../config/database.php');

$error_messages = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name_user = filter_input(INPUT_POST, 'name_user', FILTER_SANITIZE_STRING);
    $email_user = filter_input(INPUT_POST, 'email_user', FILTER_SANITIZE_EMAIL);
    $password_user = $_POST['password_user'];
    $confirmPassword_user = $_POST['confirmPassword_user'];
    if (empty($name_user) || empty($email_user) || empty($password_user) || empty($confirmPassword_user)) {
        $error_messages[] = "All fields are required.";
    }
    if (!filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Invalid email format.";
    }
    if (strlen($password_user) < 8) {
        $error_messages[] = "Password must be at least 8 characters long.";
    }
    if ($password_user !== $confirmPassword_user) {
        $error_messages[] = "Passwords do not match.";
    }

    if (empty($error_messages)) {
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email_user);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error_messages[] = "This email is already registered.";
        } else {
            $hashed_password = password_hash($password_user, PASSWORD_DEFAULT);
            $add_sql = "INSERT INTO users (name, email, password, id_role) VALUES (?, ?, ?, ?)";
            $add_stmt = $conn->prepare($add_sql);
            $role_user = 3; 
            $add_stmt->bind_param("sssi", $name_user, $email_user, $hashed_password, $role_user);

            if ($add_stmt->execute()) {
                $success_message = "Registration successful. You can now log in.";
            } else {
                $error_messages[] = "Error adding user: " . $add_stmt->error;
            }
            $add_stmt->close();
        }
        $check_stmt->close();
    }
}
if (!empty($error_messages)) {
    $_SESSION['registration_errors'] = $error_messages;
} elseif (!empty($success_message)) {
    $_SESSION['registration_success'] = $success_message;
}
header("Location: ../public/user/login.php");
exit();
?>