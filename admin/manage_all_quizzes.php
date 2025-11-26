<?php
session_start();
include("../config/db.php");

// Only admin allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include("../includes/header.php");

// Fetch all quizzes with categories
$quizzes = mysqli_query($conn, "
    SELECT q.*, c.name AS category_name
    FROM quizzes q
    LEFT JOIN categories c ON q.category_id = c.category_id
    ORDER BY q.quiz_id DESC
");
?>

<div class="container mt-4">

    <h2 class="text-center mb-4">All Quizzes & Questions</h2>

    <?php if (mysqli_num_rows($quizzes) == 0): ?>

        <div class="alert alert-warning text-center">
            No quizzes found. Add one first!
        </div>

    <?php else: ?>

        <?php while ($quiz = mysqli_fetch_assoc($quizzes)): ?>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <?php echo $quiz['title']; ?> 
                        <span class="badge bg-secondary"><?php echo $quiz['category_name'] ?: "Uncategorized"; ?></span>
                    </h5>
                </div>

                <div class="card-body">

                    <p><strong>Description:</strong> <?php echo $quiz['description']; ?></p>
                    <p><strong>Total Questions:</strong> <?php echo $quiz['total_questions']; ?></p>
                    <p><strong>Time Limit:</strong> <?php echo $quiz['time_limit']; ?> minutes</p>

                    <div class="mb-3">
                        <a href="add_question.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn btn-success btn-sm">
                            Add Question
                        </a>

                        <a href="edit_quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" class="btn btn-warning btn-sm">
                            Edit Quiz
                        </a>

                        <a href="delete_quiz.php?quiz_id=<?php echo $quiz['quiz_id']; ?>" 
                           onclick="return confirm('Delete entire quiz?')"
                           class="btn btn-danger btn-sm">
                            Delete Quiz
                        </a>
                    </div>

                    <hr>

                    <h6>Questions:</h6>

                    <?php
                    $qid = $quiz['quiz_id'];
                    $questions = mysqli_query($conn, "
                        SELECT * FROM questions WHERE quiz_id = $qid ORDER BY question_id ASC
                    ");

                    if (mysqli_num_rows($questions) == 0):
                    ?>

                        <p class="text-muted">No questions yet.</p>

                    <?php else: ?>

                        <table class="table-dark-aesthetic table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Correct</th>
                                    <th>Marks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php 
                            $i = 1;
                            while ($q = mysqli_fetch_assoc($questions)): 
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>

                                    <td><?php echo $q['question_text']; ?></td>

                                    <td class="fw-bold text-success"><?php echo $q['correct_option']; ?></td>

                                    <td><?php echo $q['marks']; ?></td>

                                    <td>
                                        <a href="edit_question.php?question_id=<?php echo $q['question_id']; ?>&quiz_id=<?php echo $qid; ?>" 
                                           class="btn btn-sm btn-warning">Edit</a>

                                        <a href="delete_question.php?question_id=<?php echo $q['question_id']; ?>&quiz_id=<?php echo $qid; ?>" 
                                           onclick="return confirm('Delete this question?')"
                                           class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                            </tbody>
                        </table>

                    <?php endif; ?>

                </div>
            </div>

        <?php endwhile; ?>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
