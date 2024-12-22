<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; 

require('../../config/database.php');  



 else {
    ?>
    <form action="" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php
}
?>
