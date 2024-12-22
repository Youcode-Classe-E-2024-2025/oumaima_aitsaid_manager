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
            
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'oumaimabellanova@gmail.com'; 
                $mail->Password = 'ytqz mnhk ecaq lcls'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                
                $mail->setFrom('oumaimabellanova@gmail.comm', 'restaurant');
                $mail->addAddress($userEmail, $userName);
                $mail->isHTML(true);
                $mail->Subject = 'Your Account Has Been Approved';
                $mail->Body = "
                    <p>Dear $userName,</p>
                    <p>Your account has been approved! You can now log in and enjoy our services.</p>
                    <p>Thank you!</p>
                ";

                $mail->send();
                echo "User approved, and email sent!";
            } catch (Exception $e) {
                echo "User approved, but email could not be sent. Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Failed to approve user.";
        }
    } else {
        echo "User not found.";
    }
}
?>
