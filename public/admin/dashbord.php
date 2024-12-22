<?php
include('../../config/database.php');
require_once __DIR__ . '/../../vendor/autoload.php';

use MathPHP\Statistics\Descriptive;

session_start(); 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== '1') 
{ 
   
    header('Location: ../user/login.php'); 
    exit();
}
$sql = "SELECT * FROM users WHERE is_archifed = 0"; 
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_NUMBER_INT);

    if ($name && $email && $password && $role) {
        // Check if the email already exists
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $_SESSION['error_message'] = "Email $email is already registered. Please use a different email.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, id_role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "User added successfully.";
            } else {
                $_SESSION['error_message'] = "Error adding user: " . $stmt->error;
            }

            $stmt->close();
        }

        $check_stmt->close();
    } else {
        $_SESSION['error_message'] = "All fields are required.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;

unset($_SESSION['success_message'], $_SESSION['error_message']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Admin Dashboard</h2>
        <div class="mb-4 text-right">
            <button id="add_user" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">Add User</button>
            <a href="archive_user.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">Archive User</a>
            <a href="../../statistic/chart.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">Statistic</a>
            <a href="../user/logout.php" onclick="return confirm('Are you sure you want to log out?');" class="inline-block bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition">Logout</a>
        </div>

        <!-- Add User Form -->
        <div id="add_user_form" class="hidden bg-white p-6 mb-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4">Add New User</h3>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select id="role" name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="1">Admin</option>
                        <option value="2">Editor</option>
                        <option value="3">Viewer</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" name="add_user" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">Add User</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Approved</th>
                        <th class="px-6 py-3 text-left">Archived</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="border-t border-gray-200">
                        <td class="px-6 py-4"><?php echo $row['id']; ?></td>
                        <td class="px-6 py-4"><?php echo $row['name']; ?></td>
                        <td class="px-6 py-4"><?php echo $row['email']; ?></td>
                        <td class="px-6 py-4"><?php echo $row['id_role']; ?></td>
                        <td class="px-6 py-4"><?php echo $row['is_approved'] ? 'Yes' : 'No'; ?></td>
                        <td class="px-6 py-4"><?php echo $row['is_archifed'] ? 'Yes' : 'No'; ?></td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="../../modal/edit_user.php?id=<?php echo $row['id']; ?>" class="bg-yellow-500 text-white py-2 px-4 rounded-md hover:bg-yellow-600">Edit</a>
                            <a href="../../modal/approve_user.php?id=<?php echo $row['id']; ?>" class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">Approve</a>
                            <a href="../../modal/delete_user.php?id=<?php echo $row['id']; ?>" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addUserButton = document.getElementById('add_user');
            const addUserForm = document.getElementById('add_user_form');

            addUserButton.addEventListener('click', function() {
                addUserForm.classList.toggle('hidden');
            });

            <?php if (isset($success_message)): ?>
    Swal.fire('Success!', '<?php echo $success_message; ?>', 'success');
<?php endif; ?>

<?php if (isset($error_message)): ?>
    Swal.fire('Error!', '<?php echo $error_message; ?>', 'error');
<?php endif; ?>

        });
    </script>
</body>
</html>