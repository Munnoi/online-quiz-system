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

if (!$quiz) {
    die("Quiz not found.");
}

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

<div class="container mt-4">

    <h2 class="text-center mb-4"><?php echo $quiz['title']; ?></h2>

    <div class="alert alert-info text-center">
        Total Questions: <strong><?php echo $quiz['total_questions']; ?></strong> |
        Time Limit: <strong><?php echo $quiz['time_limit']; ?> minutes</strong>
    </div>

    <!-- QUIZ FORM -->
    <form action="submit_quiz.php" method="POST">

        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

        <?php 
        $i = 1;
        while ($q = mysqli_fetch_assoc($questionsQuery)): 
        ?>

            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    <h5>Q<?php echo $i; ?>. <?php echo $q['question_text']; ?></h5>

                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio" 
                               name="answer[<?php echo $q['question_id']; ?>]" 
                               value="A" required>
                        <label class="form-check-label"><?php echo $q['option_a']; ?></label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="answer[<?php echo $q['question_id']; ?>]" 
                               value="B">
                        <label class="form-check-label"><?php echo $q['option_b']; ?></label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="answer[<?php echo $q['question_id']; ?>]" 
                               value="C">
                        <label class="form-check-label"><?php echo $q['option_c']; ?></label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" 
                               name="answer[<?php echo $q['question_id']; ?>]" 
                               value="D">
                        <label class="form-check-label"><?php echo $q['option_d']; ?></label>
                    </div>

                </div>
            </div>

        <?php 
        $i++;
        endwhile; 
        ?>

        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
            Submit Quiz
        </button>

    </form>

</div>

<?php include("../includes/footer.php"); ?>
