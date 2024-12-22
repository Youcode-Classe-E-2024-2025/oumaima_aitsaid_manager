<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; 

include('../config/database.php');
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $userEmail = $user['email'];
        $userName = $user['name'];


        $approveSql = "UPDATE users SET is_approved = 1 WHERE id = ?";
        $approveStmt = $conn->prepare($approveSql);
        $approveStmt->bind_param("i", $userId);

        if ($approveStmt->execute()) {
            
          
        } else {
            echo "Failed to approve user.";
        }
    } else {
        echo "User not found.";
    }
}
?>
