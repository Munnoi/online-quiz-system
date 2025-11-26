<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$question_id = $_GET['question_id'] ?? 0;
$quiz_id     = $_GET['quiz_id'] ?? 0;

if (!$question_id || !$quiz_id) {
    die("Invalid access.");
}

// Fetch question details
$qQuery = mysqli_query($conn, "SELECT * FROM questions WHERE question_id = $question_id");
$question = mysqli_fetch_assoc($qQuery);

if (!$question) {
    die("Question not found.");
}

$message = "";

// When admin submits form
if (isset($_POST['update_question'])) {

    $qtext   = mysqli_real_escape_string($conn, $_POST['question_text']);
    $optA    = mysqli_real_escape_string($conn, $_POST['option_a']);
    $optB    = mysqli_real_escape_string($conn, $_POST['option_b']);
    $optC    = mysqli_real_escape_string($conn, $_POST['option_c']);
    $optD    = mysqli_real_escape_string($conn, $_POST['option_d']);
    $correct = $_POST['correct_option'];
    $marks   = $_POST['marks'];

    $update = mysqli_query($conn, "
        UPDATE questions 
        SET 
            question_text = '$qtext',
            option_a = '$optA',
            option_b = '$optB',
            option_c = '$optC',
            option_d = '$optD',
            correct_option = '$correct',
            marks = $marks
        WHERE question_id = $question_id
    ");

    if ($update) {
        header("Location: view_questions.php?quiz_id=$quiz_id&updated=1");
        exit;
    } else {
        $message = "Failed to update question!";
    }
}

include("../includes/header.php");
?>

<div class="container mt-4" style="max-width: 700px;">

    <h2 class="text-center mb-4">Edit Question</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-danger text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="p-4 shadow-sm admin-form-card">

        <!-- Question Text -->
        <div class="mb-3">
            <label class="form-label">Question</label>
            <textarea name="question_text" class="form-control" rows="3" required><?php 
                echo $question['question_text']; ?></textarea>
        </div>

        <!-- Options -->
        <div class="mb-3">
            <label class="form-label">Option A</label>
            <input type="text" name="option_a" class="form-control"
                   value="<?php echo $question['option_a']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Option B</label>
            <input type="text" name="option_b" class="form-control"
                   value="<?php echo $question['option_b']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Option C</label>
            <input type="text" name="option_c" class="form-control"
                   value="<?php echo $question['option_c']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Option D</label>
            <input type="text" name="option_d" class="form-control"
                   value="<?php echo $question['option_d']; ?>" required>
        </div>

        <!-- Correct Option -->
        <div class="mb-3">
            <label class="form-label">Correct Option</label>
            <select name="correct_option" class="form-select" required>
                <option value="A" <?php echo ($question['correct_option']=='A')?'selected':''; ?>>A</option>
                <option value="B" <?php echo ($question['correct_option']=='B')?'selected':''; ?>>B</option>
                <option value="C" <?php echo ($question['correct_option']=='C')?'selected':''; ?>>C</option>
                <option value="D" <?php echo ($question['correct_option']=='D')?'selected':''; ?>>D</option>
            </select>
        </div>

        <!-- Marks -->
        <div class="mb-3">
            <label class="form-label">Marks</label>
            <input type="number" name="marks" class="form-control"
                   value="<?php echo $question['marks']; ?>" required min="1">
        </div>

        <button type="submit" name="update_question" class="btn btn-primary w-100">
            Update Question
        </button>

        <div class="mt-3 text-center">
            <a href="view_questions.php?quiz_id=<?php echo $quiz_id; ?>" 
               class="btn btn-outline-secondary">Back to Questions</a>
        </div>

    </form>

</div>

<?php include("../includes/footer.php"); ?>
