<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? 0;

// Fetch the quiz info
$quizQuery = mysqli_query($conn, "SELECT * FROM quizzes WHERE quiz_id = $quiz_id");
$quiz = mysqli_fetch_assoc($quizQuery);

if (!$quiz) {
    die("Quiz not found.");
}

$message = "";

// Fetch categories for dropdown
$catQuery = mysqli_query($conn, "SELECT * FROM categories");


// Update quiz
if (isset($_POST['update_quiz'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = $_POST['category_id'] ?? 'NULL';
    $total_q = $_POST['total_questions'];
    $time_limit = $_POST['time_limit'];

    $update = mysqli_query($conn, "
        UPDATE quizzes 
        SET title = '$title', 
            description = '$desc',
            category_id = $category_id,
            total_questions = $total_q,
            time_limit = $time_limit
        WHERE quiz_id = $quiz_id
    ");

    if ($update) {
        $message = "Quiz updated successfully!";
    } else {
        $message = "Failed to update quiz!";
    }
}

include("../includes/header.php");
?>

<div class="page-content d-flex justify-content-center">

    <div class="admin-form-card">

        <h2 class="text-center admin-form-title">Edit Quiz</h2>

        <?php if ($message != ""): ?>
            <div class="alert alert-info text-center mb-3">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="off">

            <!-- Title -->
            <div class="mb-3">
                <label class="form-label text-light">Quiz Title</label>
                <input type="text" name="title" 
                       class="form-control admin-input"
                       value="<?php echo $quiz['title']; ?>" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label text-light">Description</label>
                <textarea name="description" class="form-control admin-input" rows="3" required><?php 
                    echo $quiz['description']; ?></textarea>
            </div>

            <!-- Category Dropdown -->
            <div class="mb-3">
                <label class="form-label text-light">Category</label>
                <select name="category_id" class="form-select admin-input">
                    <option value="">Select Category</option>
                    <?php while ($c = mysqli_fetch_assoc($catQuery)): ?>
                        <option value="<?php echo $c['category_id']; ?>"
                            <?php if ($quiz['category_id'] == $c['category_id']) echo "selected"; ?>>
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Total Questions -->
            <div class="mb-3">
                <label class="form-label text-light">Total Questions</label>
                <input type="number" name="total_questions"
                       class="form-control admin-input"
                       value="<?php echo $quiz['total_questions']; ?>" required>
            </div>

            <!-- Time Limit -->
            <div class="mb-3">
                <label class="form-label text-light">Time Limit (minutes)</label>
                <input type="number" name="time_limit"
                       class="form-control admin-input"
                       value="<?php echo $quiz['time_limit']; ?>" required>
            </div>

            <button type="submit" name="update_quiz" class="btn btn-primary w-100 btn-custom">
                Update Quiz
            </button>

            <div class="text-center mt-3">
                <a href="view_quizzes.php" class="btn btn-outline-light admin-back-btn">
                    Back to Quizzes
                </a>
            </div>

        </form>

    </div>

</div>


<?php include("../includes/footer.php"); ?>
