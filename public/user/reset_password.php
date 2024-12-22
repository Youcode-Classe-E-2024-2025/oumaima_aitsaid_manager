<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; 

require('../../config/database.php');  

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50));  
        $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));  
        $updateSql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sss", $token, $expiry, $email);
        $updateStmt->execute();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  
            $mail->SMTPAuth = true;
            $mail->Username = 'oumaimabellanova@gmail.com';  
            $mail->Password = 'ytqz mnhk ecaq lcls'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('oumaimabellanova@gmail.com', 'restaurant');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "
                <p>Hello,</p>
                <p>We received a request to reset your password.</p>
                <p>Click the link below to reset your password:</p>
                <p><a href='http://localhost/gestion_manager/public/user/reset_password.php?token=$token'>Reset Password</a></p>
                <p>This link will expire in 1 hour.</p>
            ";

            $mail->send();
            echo 'A password reset email has been sent.';
        } catch (Exception $e) {
            echo "Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No user found with that email address.";
    }
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $sql = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['new_password'])) {
                $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $updateSql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("ss", $newPassword, $token);
                $updateStmt->execute();

                echo "Your password has been successfully reset.";
            } else {
                echo "Please enter a new password.";
            }
        }
        ?>
        <form action="" method="post">
            <label for="new_password">Enter a new password:</label>
            <input type="password" name="new_password" required>
            <button type="submit">Reset Password</button>
        </form>
        <?php
    } else {
        echo "The token is invalid or has expired.";
    }
} else {
    ?>
    <form action="" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php
}
?>
