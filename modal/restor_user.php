<?php
include('../config/database.php');
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "UPDATE users SET is_archifed = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header("Location:../public/admin/archive_user.php");
}
?>
