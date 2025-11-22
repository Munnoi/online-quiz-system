<?php
session_start();
include("../config/db.php");

// Only admin can delete quizzes
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['quiz_id']) || !is_numeric($_GET['quiz_id'])) {
    die("Invalid Quiz ID!");
}

$quiz_id = intval($_GET['quiz_id']); // ensures it's an integer

$stmt = $conn->prepare("DELETE FROM quizzes WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);

if ($stmt->execute()) {
    header("Location: view_quizzes.php?deleted=1");
    exit;
} else {
    echo "Failed to delete quiz!";
}

$stmt->close();
$conn->close();
?>

