<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("../includes/header.php");
?>

<div class="container mt-5">
    <div class="p-4 bg-light shadow rounded">

        <h2 class="mb-3">Welcome, <?php echo $_SESSION['name']; ?> 👋</h2>
        <p class="text-muted">This is your dashboard.</p>

        <hr>

        <div class="row">

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">My Profile</h5>
                        <p class="card-text">View and edit your account details.</p>
                        <a href="profile.php" class="btn btn-primary">Open</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Available Quizzes</h5>
                        <p class="card-text">Attempt quizzes assigned to you.</p>
                        <a href="quiz_list.php" class="btn btn-success">View</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">My Results</h5>
                        <p class="card-text">Check your completed quiz scores.</p>
                        <a href="result.php" class="btn btn-info">Check</a>
                    </div>
                </div>
            </div>

        </div>

        <hr>

        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>

    </div>
</div>

<?php include("../includes/footer.php"); ?>
