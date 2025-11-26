<?php
session_start();
include("../config/db.php");

// Only admin can delete questions
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$question_id = $_GET['question_id'] ?? 0;
$quiz_id     = $_GET['quiz_id'] ?? 0;

if (!$question_id || !$quiz_id) {
    die("Invalid access.");
}

// Delete the question
$delete = mysqli_query($conn, "
    DELETE FROM questions 
    WHERE question_id = $question_id
");

if ($delete) {
    header("Location: view_questions.php?quiz_id=$quiz_id&deleted=1");
    exit;
} else {
    echo "Failed to delete question!";
}
?>
