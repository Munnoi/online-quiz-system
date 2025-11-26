<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Get quiz ID
$quiz_id = $_GET['quiz_id'] ?? 0;

// Fetch quiz details
$quizQuery = mysqli_query($conn, "SELECT title FROM quizzes WHERE quiz_id = $quiz_id");
$quiz = mysqli_fetch_assoc($quizQuery);

if (!$quiz) {
    die("Quiz not found.");
}

// Fetch questions of this quiz
$questionsQuery = mysqli_query($conn, "
    SELECT * FROM questions 
    WHERE quiz_id = $quiz_id
    ORDER BY question_id ASC
");

include("../includes/header.php");
?>

<div class="container mt-4">

    <h2 class="mb-4 text-center">
        Questions for: <span class="text-primary"><?php echo $quiz['title']; ?></span>
    </h2>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success text-center">Question added successfully!</div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger text-center">Question deleted successfully!</div>
    <?php endif; ?>

    <div class="text-end mb-3">
        <a href="add_question.php?quiz_id=<?php echo $quiz_id; ?>" 
           class="btn btn-success">Add New Question</a>
    </div>

    <?php if (mysqli_num_rows($questionsQuery) == 0): ?>

        <div class="alert alert-warning text-center">
            No questions found. Add some now!
        </div>

    <?php else: ?>

        <table class="table-dark-aesthetic table-bordered table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Correct</th>
                    <th>Marks</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $i = 1;
                while ($q = mysqli_fetch_assoc($questionsQuery)): 
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>

                    <td><?php echo $q['question_text']; ?></td>

                    <td>
                        A: <?php echo $q['option_a']; ?><br>
                        B: <?php echo $q['option_b']; ?><br>
                        C: <?php echo $q['option_c']; ?><br>
                        D: <?php echo $q['option_d']; ?>
                    </td>

                    <td class="fw-bold text-success">
                        <?php echo $q['correct_option']; ?>
                    </td>

                    <td><?php echo $q['marks']; ?></td>

                    <td>
                        <a href="edit_question.php?question_id=<?php echo $q['question_id']; ?>&quiz_id=<?php echo $quiz_id; ?>" 
                           class="btn btn-sm btn-warning mb-1">Edit</a>

                        <a href="delete_question.php?question_id=<?php echo $q['question_id']; ?>&quiz_id=<?php echo $quiz_id; ?>" 
                           onclick="return confirm('Delete this question?')"
                           class="btn btn-sm btn-danger mb-1">Delete</a>
                    </td>
                </tr>

                <?php endwhile; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
