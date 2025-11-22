<?php
session_start();
include("../config/db.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include("../includes/header.php");

// Fetch quizzes
$query = mysqli_query($conn, "
    SELECT quizzes.*, categories.name AS category_name
    FROM quizzes
    LEFT JOIN categories ON quizzes.category_id = categories.category_id
");
?>

<div class="page-content">

    <h2 class="text-center mb-4 quiz-title">Available Quizzes</h2>

    <?php if (mysqli_num_rows($query) == 0): ?>
        <div class="alert alert-warning text-center">
            No quizzes available right now.
        </div>
    <?php else: ?>

        <div class="quiz-grid row">

            <?php while ($quiz = mysqli_fetch_assoc($query)): ?>

                <div class="col-md-4 mb-4">
                    <div class="quiz-card">

                        <h4 class="quiz-card-title"><?php echo $quiz['title']; ?></h4>

                        <p class="quiz-card-desc">
                            <?php echo $quiz['description']; ?>
                        </p>

                        <p class="quiz-card-info">
                            <strong>Category:</strong>
                            <?php echo $quiz['category_name'] ?? "Uncategorized"; ?>
                        </p>

                        <p class="quiz-card-info">
                            <strong>Total Questions:</strong>
                            <?php echo $quiz['total_questions']; ?>
                        </p>

                        <p class="quiz-card-info">
                            <strong>Time Limit:</strong>
                            <?php echo $quiz['time_limit']; ?> minutes
                        </p>

                        <a href="take_quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>"
                           class="btn btn-primary w-100 quiz-btn">
                           Start Quiz
                        </a>

                    </div>
                </div>

            <?php endwhile; ?>

        </div>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
