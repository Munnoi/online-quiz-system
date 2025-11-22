<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include("../includes/header.php");

// Fetch all results with user and quiz info
$query = mysqli_query($conn, "
    SELECT r.*, 
           u.name AS username, 
           q.title AS quiz_title
    FROM results r
    JOIN users u ON r.user_id = u.user_id
    JOIN quizzes q ON r.quiz_id = q.quiz_id
    ORDER BY r.attempted_at DESC
");
?>

<div class="page-content">

    <h2 class="text-center mb-4 admin-title">All Quiz Results</h2>

    <?php if (mysqli_num_rows($query) == 0): ?>

        <div class="alert alert-warning text-center admin-empty-alert">
            No results found.
        </div>

    <?php else: ?>

        <div class="admin-table-wrapper">

            <table class="table-dark-aesthetic">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Quiz Title</th>
                        <th>Score</th>
                        <th>Total Marks</th>
                        <th>Date</th>
                        <th>View Result</th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($query)): 
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>

                        <td><?php echo $row['username']; ?></td>

                        <td><?php echo $row['quiz_title']; ?></td>

                        <td class="fw-bold text-success">
                            <?php echo $row['score']; ?>
                        </td>

                        <td><?php echo $row['total_marks']; ?></td>

                        <td><?php echo $row['attempted_at']; ?></td>

                        <td>
                            <a href="../user/result.php?quiz_id=<?php echo $row['quiz_id']; ?>&user_id=<?php echo $row['user_id']; ?>"
                               class="btn btn-sm btn-info admin-btn">
                                View
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
