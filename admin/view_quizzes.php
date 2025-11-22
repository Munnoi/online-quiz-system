<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch all quizzes with category name
$query = mysqli_query($conn, "
    SELECT q.*, c.name AS category_name
    FROM quizzes q
    LEFT JOIN categories c ON q.category_id = c.category_id
    ORDER BY q.quiz_id DESC
");
?>

<?php include("../includes/header.php"); ?>

<div class="page-content">

    <h2 class="text-center mb-4 admin-title">Manage Quizzes</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger text-center mb-3">
            Quiz deleted successfully.
        </div>
    <?php endif; ?>

    <div class="text-end mb-3">
        <a href="add_quiz.php" class="btn btn-primary admin-add-btn">+ Add New Quiz</a>
    </div>

    <?php if (mysqli_num_rows($query) == 0): ?>

        <div class="alert alert-warning text-center admin-empty-alert">
            No quizzes found. Add one now!
        </div>

    <?php else: ?>

        <div class="admin-table-wrapper">

            <table class="table-dark-aesthetic">
                <thead>
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
                               class="btn btn-sm btn-warning admin-btn">
                                Edit
                            </a>

                            <a href="view_questions.php?quiz_id=<?php echo $row['quiz_id']; ?>" 
                               class="btn btn-sm btn-info admin-btn">
                                Questions
                            </a>

                            <a href="delete_quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>" 
                               class="btn btn-sm btn-danger admin-btn"
                               onclick="return confirm('Are you sure you want to delete this quiz?')">
                                Delete
                            </a>

                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
