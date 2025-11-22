<?php
session_start();
include("../config/db.php");

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$user_id = $_GET['user_id'] ?? 0;

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT role FROM users WHERE user_id = $user_id"));

// Toggle role
$newRole = ($user['role'] == 'admin') ? 'user' : 'admin';

mysqli_query($conn, "UPDATE users SET role = '$newRole' WHERE user_id = $user_id");

header("Location: manage_users.php?role_updated=1");
exit;
