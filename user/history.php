<?php
session_start();
include("../config/db.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

include("../includes/header.php");

// Fetch all quiz attempts by this user
$historyQuery = mysqli_query($conn, "
    SELECT r.*, q.title 
    FROM results r
    JOIN quizzes q ON r.quiz_id = q.quiz_id
    WHERE r.user_id = $user_id
    ORDER BY r.attempted_at DESC
");
?>

<div class="container mt-4">

    <h2 class="text-center mb-4">My Quiz History</h2>

    <?php if (mysqli_num_rows($historyQuery) == 0): ?>

        <div class="alert alert-info text-center">
            You haven't attempted any quizzes yet.
        </div>

    <?php else: ?>

        <table class="table table-bordered table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Quiz Title</th>
                    <th>Score</th>
                    <th>Total Marks</th>
                    <th>Date & Time</th>
                    <th>View</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($historyQuery)): 
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><strong class="text-success"><?php echo $row['score']; ?></strong></td>
                    <td><?php echo $row['total_marks']; ?></td>
                    <td><?php echo $row['attempted_at']; ?></td>
                    <td>
                        <a href="result.php?quiz_id=<?php echo $row['quiz_id']; ?>" 
                           class="btn btn-sm btn-primary">
                            View Result
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
