<?php
session_start();
include("../config/db.php");

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$quiz_id = $_GET['quiz_id'] ?? 0;

// Fetch quiz name
$quizQuery = mysqli_query($conn, "SELECT title FROM quizzes WHERE quiz_id = $quiz_id");
$quiz = mysqli_fetch_assoc($quizQuery);

if (!$quiz) {
    die("Quiz not found.");
}

$message = "";

// Handle form submission
if (isset($_POST['add_question'])) {

    $question = mysqli_real_escape_string($conn, $_POST['question_text']);
    $optionA  = mysqli_real_escape_string($conn, $_POST['option_a']);
    $optionB  = mysqli_real_escape_string($conn, $_POST['option_b']);
    $optionC  = mysqli_real_escape_string($conn, $_POST['option_c']);
    $optionD  = mysqli_real_escape_string($conn, $_POST['option_d']);
    $correct  = $_POST['correct_option'];
    $marks    = $_POST['marks'];

    $insert = mysqli_query($conn, "
        INSERT INTO questions 
        (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option, marks)
        VALUES 
        ($quiz_id, '$question', '$optionA', '$optionB', '$optionC', '$optionD', '$correct', $marks)
    ");

    if ($insert) {
        header("Location: view_questions.php?quiz_id=$quiz_id&added=1");
        exit;
    } else {
        $message = "Failed to add question!";
    }
}

include("../includes/header.php");
?>

<div class="page-content d-flex justify-content-center">

    <div class="admin-form-card">

        <h2 class="text-center admin-form-title">
            Add Question to: <span class="text-primary"><?php echo $quiz['title']; ?></span>
        </h2>

        <?php if ($message != ""): ?>
            <div class="alert alert-danger text-center mb-3"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="off">

            <!-- Question -->
            <div class="mb-3">
                <label class="form-label text-light">Question</label>
                <textarea name="question_text" class="form-control admin-input" rows="3" required></textarea>
            </div>

            <!-- Options -->
            <div class="mb-3">
                <label class="form-label text-light">Option A</label>
                <input type="text" name="option_a" class="form-control admin-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Option B</label>
                <input type="text" name="option_b" class="form-control admin-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Option C</label>
                <input type="text" name="option_c" class="form-control admin-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Option D</label>
                <input type="text" name="option_d" class="form-control admin-input" required>
            </div>

            <!-- Correct Option -->
            <div class="mb-3">
                <label class="form-label text-light">Correct Option</label>
                <select name="correct_option" class="form-select admin-input" required>
                    <option value="">Select</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>

            <!-- Marks -->
            <div class="mb-3">
                <label class="form-label text-light">Marks</label>
                <input type="number" name="marks" class="form-control admin-input" required min="1">
            </div>

            <button type="submit" name="add_question" class="btn btn-success w-100 btn-custom">
                Add Question
            </button>

            <div class="text-center mt-3">
                <a href="view_questions.php?quiz_id=<?php echo $quiz_id; ?>" 
                class="btn btn-outline-light admin-back-btn">
                    Back to Questions
                </a>
            </div>

        </form>

    </div>

</div>

<?php include("../includes/footer.php"); ?>
