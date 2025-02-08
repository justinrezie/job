<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

$query = "SELECT * FROM users";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $user['id'] . "</td>
                <td>" . $user['username'] . "</td>
                <td>" . $user['role'] . "</td>
                <td>
                    <a href='set_admin.php?id=" . $user['id'] . "'>Set Admin</a> | 
                    <a href='delete_user.php?id=" . $user['id'] . "'>Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No users found.</td></tr>";
}
?>
