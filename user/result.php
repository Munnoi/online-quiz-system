<?php
session_start();
include("../config/db.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$quiz_id = $_GET['quiz_id'] ?? 0;
$quiz_user_id = $_GET['user_id'] ?? $user_id;

// Fetch quiz info
$quizQuery = mysqli_query($conn, "SELECT * FROM quizzes WHERE quiz_id = $quiz_id");
$quiz = mysqli_fetch_assoc($quizQuery);

// Fetch result info
$resultQuery = mysqli_query($conn, "
    SELECT * FROM results 
    WHERE user_id = $quiz_user_id AND quiz_id = $quiz_id
    ORDER BY attempted_at DESC LIMIT 1
");

$result = mysqli_fetch_assoc($resultQuery);

if (!$result) {
    die("Result not found.");
}

$score = $result['score'];
$total_marks = $result['total_marks'];

// Fetch answers for detailed report
$answersQuery = mysqli_query($conn, "
    SELECT a.*, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option
    FROM answers a
    JOIN questions q ON a.question_id = q.question_id
    WHERE a.user_id = $quiz_user_id AND a.quiz_id = $quiz_id
");
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-4">

    <h2 class="text-center mb-4">Quiz Result</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">

            <h3><?php echo $quiz['title']; ?></h3>

            <h4 class="mt-3">
                Score: 
                <span class="text-success">
                    <?php echo $score; ?>
                </span> 
                / <?php echo $total_marks; ?>
            </h4>

            <?php
            $correct_count = mysqli_num_rows(mysqli_query($conn, "
                SELECT answer_id FROM answers 
                WHERE user_id = $user_id AND quiz_id = $quiz_id AND is_correct = 1
            "));

            $wrong_count = mysqli_num_rows(mysqli_query($conn, "
                SELECT answer_id FROM answers 
                WHERE user_id = $user_id AND quiz_id = $quiz_id AND is_correct = 0
            "));
            ?>

            <p class="mt-3">
                <strong>Correct Answers:</strong> <?php echo $correct_count; ?><br>
                <strong>Wrong Answers:</strong> <?php echo $wrong_count; ?>
            </p>

            <a href="quiz_list.php" class="btn btn-primary mt-3">Back to Quizzes</a>
        </div>
    </div>

    <!-- Detailed Report -->
    <h4 class="mb-3 text-light">Detailed Report</h4>

    <div class="accordion dark-accordion" id="resultAccord">

        <?php 
        $i = 1;
        while ($ans = mysqli_fetch_assoc($answersQuery)): 
            $correct = $ans['correct_option'];
            $selected = $ans['selected_option'];
        ?>

        <div class="accordion-item dark-acc-item">

            <h2 class="accordion-header" id="heading<?php echo $i; ?>">
                <button class="accordion-button collapsed dark-acc-btn" type="button" 
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse<?php echo $i; ?>">
                    Question <?php echo $i; ?>
                </button>
            </h2>

            <div id="collapse<?php echo $i; ?>" class="accordion-collapse collapse">
                <div class="accordion-body dark-acc-body">

                    <p class="text-light"><strong>Q<?php echo $i; ?>:</strong> <?php echo $ans['question_text']; ?></p>

                    <p class="text-light">
                        <strong>Your Answer:</strong> 
                        <span class="<?php echo ($selected == $correct) ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $selected; ?>
                        </span>
                    </p>

                    <p class="text-light">
                        <strong>Correct Answer:</strong> 
                        <span class="text-success"><?php echo $correct; ?></span>
                    </p>

                </div>
            </div>

        </div>

        <?php $i++; endwhile; ?>

    </div>


</div>

<?php include("../includes/footer.php"); ?>
