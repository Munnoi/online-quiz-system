<?php
session_start();
include("../config/db.php");

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = "";

// Fetch categories
$catQuery = mysqli_query($conn, "SELECT * FROM categories");

// Insert quiz
if (isset($_POST['add_quiz'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = $_POST['category_id'] ?? 'NULL';
    $total_q = $_POST['total_questions'];
    $time_limit = $_POST['time_limit'];
    $created_by = $_SESSION['user_id'];

    $insert = mysqli_query($conn, "
        INSERT INTO quizzes (title, description, category_id, total_questions, time_limit, created_by, created_at)
        VALUES ('$title', '$desc', $category_id, $total_q, $time_limit, $created_by, NOW())
    ");

    if ($insert) {
        header("Location: view_quizzes.php?added=1");
        exit;
    } else {
        $message = "Failed to add quiz!";
    }
}

include("../includes/header.php");
?>

<div class="page-content d-flex justify-content-center">

    <div class="admin-form-card">

        <h2 class="text-center admin-form-title">Add New Quiz</h2>

        <?php if ($message != ""): ?>
            <div class="alert alert-danger text-center mb-3"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="off">

            <!-- Title -->
            <div class="mb-3">
                <label class="form-label text-light">Quiz Title</label>
                <input type="text" name="title" class="form-control admin-input" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label class="form-label text-light">Description</label>
                <textarea name="description" class="form-control admin-input" rows="3" required></textarea>
            </div>

            <!-- Category Dropdown -->
            <div class="mb-3">
                <label class="form-label text-light">Category</label>
                <select name="category_id" class="form-select admin-input">
                    <option value="">Select Category</option>
                    <?php while ($c = mysqli_fetch_assoc($catQuery)): ?>
                        <option value="<?php echo $c['category_id']; ?>">
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Total Questions -->
            <div class="mb-3">
                <label class="form-label text-light">Total Questions</label>
                <input type="number" name="total_questions" class="form-control admin-input" required min="1">
            </div>

            <!-- Time Limit -->
            <div class="mb-3">
                <label class="form-label text-light">Time Limit (minutes)</label>
                <input type="number" name="time_limit" class="form-control admin-input" required min="1">
            </div>

            <button type="submit" name="add_quiz" class="btn btn-success w-100 btn-custom">
                Add Quiz
            </button>

            <div class="text-center mt-3">
                <a href="view_quizzes.php" class="btn btn-outline-light admin-back-btn">Back to Quizzes</a>
            </div>

        </form>

    </div>

</div>

<?php include("../includes/footer.php"); ?>
