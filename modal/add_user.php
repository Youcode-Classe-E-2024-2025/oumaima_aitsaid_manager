<?php
header('Content-Type: application/json');
require_once('../config/database.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $names = $_POST['name'] ?? [];
    $emails = $_POST['email'] ?? [];
    $passwords = $_POST['password'] ?? [];
    $roles = $_POST['role'] ?? [];

    if (empty($names) || empty($emails) || empty($passwords) || empty($roles)) {
        throw new Exception('Missing required data');
    }

    $errors = [];
    $successCount = 0;

    $conn->begin_transaction();

    foreach ($names as $index => $name) {
        $email = filter_var($emails[$index], FILTER_SANITIZE_EMAIL);
        $password = $passwords[$index];
        $role = filter_var($roles[$index], FILTER_SANITIZE_NUMBER_INT);

        // Validate inputs
        if (empty($name) || empty($email) || empty($password) || empty($role)) {
            $errors[] = "Error: Missing data for user $index.";
            continue;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Error: Invalid email format for user $index.";
            continue;
        }

        // Check if email exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Error: Email $email is already registered.";
            continue;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $insert_sql = "INSERT INTO users (name, email, password, id_role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssi", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $successCount++;
        } else {
            $errors[] = "Error adding user $name: " . $stmt->error;
        }
    }

    if (empty($errors)) {
        $conn->commit();
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => "$successCount users added successfully."
        ]);
    } else {
        $conn->rollback();
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'There were errors while adding users.',
            'errors' => $errors
        ]);
    }
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

$conn->close();
?>