<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include("../includes/header.php");

// Fetch all quizzes
$query = mysqli_query($conn, "
    SELECT q.*, c.name AS category_name
    FROM quizzes q
    LEFT JOIN categories c ON q.category_id = c.category_id
    ORDER BY q.quiz_id DESC
");
?>

<div class="container mt-4">

    <h2 class="mb-4 text-center">Manage Quizzes</h2>

    <div class="text-end mb-3">
        <a href="add_quiz.php" class="btn btn-primary">Add New Quiz</a>
    </div>

    <?php if (mysqli_num_rows($query) == 0): ?>

        <div class="alert alert-warning text-center">
            No quizzes found. Add one now!
        </div>

    <?php else: ?>

        <table class="table table-bordered table-striped table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Quiz Title</th>
                    <th>Category</th>
                    <th>Total Questions</th>
                    <th>Time Limit</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $i = 1;
                while ($row = mysqli_fetch_assoc($query)):
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>

                    <td><?php echo $row['title']; ?></td>

                    <td><?php echo $row['category_name'] ?? 'Uncategorized'; ?></td>

                    <td><?php echo $row['total_questions']; ?></td>

                    <td><?php echo $row['time_limit']; ?> min</td>

                    <td><?php echo $row['created_at']; ?></td>

                    <td>

                        <a href="edit_quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>" 
                           class="btn btn-sm btn-warning mb-1">
                            Edit
                        </a>

                        <a href="view_questions.php?quiz_id=<?php echo $row['quiz_id']; ?>" 
                           class="btn btn-sm btn-info mb-1">
                            Questions
                        </a>

                        <a href="delete_quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>" 
                           class="btn btn-sm btn-danger mb-1"
                           onclick="return confirm('Are you sure you want to delete this quiz?')">
                            Delete
                        </a>

                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
