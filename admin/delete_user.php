<?php
session_start();
include("../config/db.php");

// Only admin can delete users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$user_id = $_GET['user_id'] ?? 0;

mysqli_query($conn, "DELETE FROM users WHERE user_id = $user_id");

header("Location: manage_users.php?deleted=1");
exit;
?>
