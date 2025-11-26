<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? 0;
$user_id = $_GET['user_id'] ?? 0;

if (!$quiz_id || !$user_id) {
    die("Invalid access.");
}

// Fetch quiz info
$quizQuery = mysqli_query($conn, "SELECT * FROM quizzes WHERE quiz_id = $quiz_id");
$quiz = mysqli_fetch_assoc($quizQuery);

// Fetch user info
$userQuery = mysqli_query($conn, "SELECT name FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($userQuery);

// Fetch result info
$resultQuery = mysqli_query($conn, "
    SELECT * FROM results 
    WHERE user_id = $user_id AND quiz_id = $quiz_id
    ORDER BY attempted_at DESC LIMIT 1
");
$result = mysqli_fetch_assoc($resultQuery);

if (!$result) {
    die("Result not found for this user.");
}

// Fetch answers for detailed view
$answersQuery = mysqli_query($conn, "
    SELECT a.*, 
           q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_option
    FROM answers a
    JOIN questions q ON a.question_id = q.question_id
    WHERE a.user_id = $user_id AND a.quiz_id = $quiz_id
");
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-4">

    <h2 class="text-center mb-3">Admin Result Viewer</h2>

    <h4 class="text-center">
        Quiz: <span class="text-primary"><?php echo $quiz['title']; ?></span>
    </h4>

    <h5 class="text-center mt-2">
        Student: <span class="text-success"><?php echo $user['name']; ?></span>
    </h5>

    <div class="card shadow-sm mt-4 mb-4">
        <div class="card-body text-center">

            <h3>
                Score: 
                <span class="text-success"><?php echo $result['score']; ?></span>
                / <?php echo $result['total_marks']; ?>
            </h3>

            <?php
            // correct and wrong count
            $correct_count = mysqli_num_rows(mysqli_query($conn, "
                SELECT answer_id FROM answers 
                WHERE user_id=$user_id AND quiz_id=$quiz_id AND is_correct=1
            "));
            $wrong_count = mysqli_num_rows(mysqli_query($conn, "
                SELECT answer_id FROM answers 
                WHERE user_id=$user_id AND quiz_id=$quiz_id AND is_correct=0
            "));
            ?>

            <p class="mt-3">
                <strong>Correct:</strong> <?php echo $correct_count; ?> |
                <strong>Wrong:</strong> <?php echo $wrong_count; ?>
            </p>

            <p><strong>Attempted At:</strong> <?php echo $result['attempted_at']; ?></p>

            <a href="view_results.php" class="btn btn-primary mt-3">Back to Results</a>

        </div>
    </div>

    <!-- Detailed Breakdown -->
    <h4 class="mb-3">Detailed Answers</h4>

    <div class="accordion" id="adminResultAcc">

        <?php 
        $i = 1;
        while ($ans = mysqli_fetch_assoc($answersQuery)): 
            $correct = $ans['correct_option'];
            $selected = $ans['selected_option'];
        ?>

            <div class="accordion-item">
                
                <h2 class="accordion-header" id="heading<?php echo $i; ?>">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse<?php echo $i; ?>">
                        Question <?php echo $i; ?>
                    </button>
                </h2>

                <div id="collapse<?php echo $i; ?>" class="accordion-collapse collapse">
                    <div class="accordion-body">

                        <p><strong>Q<?php echo $i; ?>:</strong> <?php echo $ans['question_text']; ?></p>

                        <p><strong>User Answer:</strong>
                            <span class="<?php echo ($selected == $correct) ? 'text-success' : 'text-danger'; ?>">
                                <?php echo $selected ?: 'Not Answered'; ?>
                            </span>
                        </p>

                        <p><strong>Correct Answer:</strong>
                            <span class="text-success"><?php echo $correct; ?></span>
                        </p>

                    </div>
                </div>

            </div>

        <?php $i++; endwhile; ?>

    </div>

</div>

<?php include("../includes/footer.php"); ?>
