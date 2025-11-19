<?php
session_start();
include("../config/db.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include("../includes/header.php");

// Fetch all quizzes with category name
$query = mysqli_query($conn, "
    SELECT quizzes.*, categories.name AS category_name
    FROM quizzes
    LEFT JOIN categories ON quizzes.category_id = categories.category_id
");
?>

<div class="container mt-4">

    <h2 class="mb-4 text-center">Available Quizzes</h2>

    <?php if (mysqli_num_rows($query) == 0): ?>
        <div class="alert alert-warning text-center">
            No quizzes available right now.
        </div>
    <?php else: ?>

        <div class="row">

            <?php while ($quiz = mysqli_fetch_assoc($query)): ?>

                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">

                        <div class="card-body">
                            <h5 class="card-title"><?php echo $quiz['title']; ?></h5>

                            <p class="card-text text-muted">
                                <?php echo $quiz['description']; ?>
                            </p>

                            <p class="mb-1">
                                <strong>Category:</strong>
                                <?php echo $quiz['category_name'] ?? "Uncategorized"; ?>
                            </p>

                            <p class="mb-1">
                                <strong>Total Questions:</strong>
                                <?php echo $quiz['total_questions']; ?>
                            </p>

                            <p>
                                <strong>Time Limit:</strong>
                                <?php echo $quiz['time_limit']; ?> minutes
                            </p>

                        </div>

                        <div class="card-footer bg-transparent text-center">
                            <a href="take_quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>"
                               class="btn btn-primary w-100">
                                Start Quiz
                            </a>
                        </div>

                    </div>
                </div>

            <?php endwhile; ?>

        </div>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
