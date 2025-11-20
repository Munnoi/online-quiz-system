<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if quiz_id exists
if (!isset($_POST['quiz_id'])) {
    die("Invalid Quiz.");
}

$quiz_id = $_POST['quiz_id'];
$answers = $_POST['answer'] ?? [];

// Fetch all questions for this quiz
$questionsQuery = mysqli_query($conn, "
    SELECT question_id, correct_option, marks 
    FROM questions 
    WHERE quiz_id = $quiz_id
");

$total_marks = 0;
$score = 0;

// Loop through all questions
while ($q = mysqli_fetch_assoc($questionsQuery)) {

    $qid = $q['question_id'];
    $correct = $q['correct_option'];
    $marks = $q['marks'];
    $total_marks += $marks;

    // User selected option
    $selected = $answers[$qid] ?? null;

    // Determine if correct
    $is_correct = ($selected == $correct) ? 1 : 0;

    if ($is_correct) {
        $score += $marks;
    }

    // Insert into answers table
    mysqli_query($conn, "
        INSERT INTO answers (user_id, quiz_id, question_id, selected_option, is_correct)
        VALUES ($user_id, $quiz_id, $qid, '$selected', $is_correct)
    ");
}

// Store final result in results table
mysqli_query($conn, "
    INSERT INTO results (user_id, quiz_id, score, total_marks, attempted_at)
    VALUES ($user_id, $quiz_id, $score, $total_marks, NOW())
");

// Redirect to result page
header("Location: result.php?quiz_id=$quiz_id");
exit;

?>
