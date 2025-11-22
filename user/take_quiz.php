<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$quiz_id = $_GET['quiz_id'] ?? 0;

// Fetch quiz info
$quizQuery = mysqli_query($conn, "SELECT * FROM quizzes WHERE quiz_id = $quiz_id");
$quiz = mysqli_fetch_assoc($quizQuery);

if (!$quiz) { die("Quiz not found."); }

// Fetch questions
$questionsQuery = mysqli_query($conn, "
    SELECT * FROM questions 
    WHERE quiz_id = $quiz_id
    ORDER BY question_id ASC
");

if (mysqli_num_rows($questionsQuery) == 0) {
    die("No questions found in this quiz.");
}

include("../includes/header.php");
?>

<div class="page-content">

    <div class="quiz-header-box text-center">
        <h2 class="quiz-title"><?php echo $quiz['title']; ?></h2>

        <div class="quiz-meta">
            <span>Total Questions: <strong><?php echo $quiz['total_questions']; ?></strong></span>
            <span class="mx-3">|</span>
            <span>Time Limit: <strong><?php echo $quiz['time_limit']; ?> minutes</strong></span>
        </div>
    </div>

    <!-- QUIZ FORM -->
    <form action="submit_quiz.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

        <?php 
        $i = 1;
        while ($q = mysqli_fetch_assoc($questionsQuery)): ?>

            <div class="quiz-question-card mb-4">

                <h5 class="quiz-q-title">Q<?php echo $i; ?>. <?php echo $q['question_text']; ?></h5>

                <label class="quiz-option">
                    <input type="radio" name="answer[<?php echo $q['question_id']; ?>]" value="A" required>
                    <span><?php echo $q['option_a']; ?></span>
                </label>

                <label class="quiz-option">
                    <input type="radio" name="answer[<?php echo $q['question_id']; ?>]" value="B">
                    <span><?php echo $q['option_b']; ?></span>
                </label>

                <label class="quiz-option">
                    <input type="radio" name="answer[<?php echo $q['question_id']; ?>]" value="C">
                    <span><?php echo $q['option_c']; ?></span>
                </label>

                <label class="quiz-option">
                    <input type="radio" name="answer[<?php echo $q['question_id']; ?>]" value="D">
                    <span><?php echo $q['option_d']; ?></span>
                </label>

            </div>

        <?php $i++; endwhile; ?>

        <button type="submit" class="btn btn-primary btn-lg w-100 quiz-submit-btn">
            Submit Quiz
        </button>

    </form>

</div>

<?php include("../includes/footer.php"); ?>
