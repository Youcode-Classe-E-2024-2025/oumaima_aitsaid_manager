<?php
include('../../config/database.php');

$sql = "SELECT * FROM users WHERE is_archifed = 0"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Admin Dashboard</h2>
        <div class="mb-4 text-right">
            <button id="add_user" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">Add User</button>
            <a href="archive_user.php" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition">Archive User</a>
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

   

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

