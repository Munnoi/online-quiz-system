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

// If quiz doesn't exist
if (!$quiz) {
    die("Quiz not found.");
}

// Fetch all questions for this quiz
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

    <div class="text-end mb-3">
        <a href="add_question.php?quiz_id=<?php echo $quiz_id; ?>" 
           class="btn btn-success">Add New Question</a>
    </div>

    <?php if (mysqli_num_rows($questionsQuery) == 0): ?>

        <div class="alert alert-warning text-center">
            No questions found. Add one now!
        </div>

    <?php else: ?>

            <div class="admin-table-wrapper">
                <table class="table-dark-aesthetic">
                    <thead>
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
                                A. <?php echo $q['option_a']; ?><br>
                                B. <?php echo $q['option_b']; ?><br>
                                C. <?php echo $q['option_c']; ?><br>
                                D. <?php echo $q['option_d']; ?>
                            </td>

                            <td class="fw-bold text-success">
                                <?php echo $q['correct_option']; ?>
                            </td>

                            <td><?php echo $q['marks']; ?></td>

                            <td>
                                <a href="edit_question.php?question_id=<?php echo $q['question_id']; ?>&quiz_id=<?php echo $quiz_id; ?>" 
                                    class="btn btn-sm btn-warning admin-btn">
                                    Edit
                                </a>

                                <a href="delete_question.php?question_id=<?php echo $q['question_id']; ?>&quiz_id=<?php echo $quiz_id; ?>" 
                                    class="btn btn-sm btn-danger admin-btn"
                                    onclick="return confirm('Delete this question?')">
                                    Delete
                                </a>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
