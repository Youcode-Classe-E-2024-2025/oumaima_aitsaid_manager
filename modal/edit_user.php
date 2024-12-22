<?php
include("../config/database.php"); 
$user_id = null;
$user = null;

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $user_id = $_POST['id'];  
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $update_sql = "UPDATE users SET name = ?, email = ?, id_role = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssii", $name, $email, $role, $user_id);
    if ($update_stmt->execute()) {
        header("Location: ../public/admin/dashbord.php");
        exit(); 
    } else {
        echo "Error updating user: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

<div class="relative">
    <button id="editBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</button>
    <div id="editForm" class="absolute top-0 right-0 w-1/3 bg-white p-6 shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out">
        <h3 class="text-xl font-semibold mb-4">Edit User</h3>
        <form id="userForm" action="" method="POST">
            <input type="hidden" name="id" value="<?php echo isset($user['id']) ? $user['id'] : ''; ?>">
            <label for="name" class="block mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 mb-4 border rounded" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" placeholder="Enter name">

            <label for="email" class="block mb-2">Email:</label>
            <input type="email" id="email" name="email" class="w-full p-2 mb-4 border rounded" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" placeholder="Enter email">

            <label for="role" class="block mb-2">Role:</label>
            <select id="role" name="role" class="w-full p-2 mb-4 border rounded">
                <option value="1" <?php echo (isset($user['id_role']) && $user['id_role'] == 1) ? 'selected' : ''; ?>>Admin</option>
                <option value="2" <?php echo (isset($user['id_role']) && $user['id_role'] == 2) ? 'selected' : ''; ?>>Moderator</option>
                <option value="3" <?php echo (isset($user['id_role']) && $user['id_role'] == 3) ? 'selected' : ''; ?>>User</option>
            </select>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
            <button type="button" id="closeBtn" class="bg-red-500 text-white px-4 py-2 rounded ml-2">Cancel</button>
        </form>
    </div>
</div>

</body>
</html>
