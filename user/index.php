<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("../includes/header.php");
?>

<div class="page-content">

    <div class="dashboard-container">

        <h2 class="dashboard-title">
            Welcome, <?php echo $_SESSION['name']; ?> 👋
        </h2>

        <p class="dashboard-subtitle">
            This is your personal dashboard — choose where to go next.
        </p>

        <div class="dashboard-cards row">

            <div class="col-md-4 mb-4">
                <div class="dash-card">
                    <h5 class="dash-card-title">My Profile</h5>
                    <p class="dash-card-text">View and update your account details.</p>
                    <a href="profile.php" class="btn btn-primary dash-btn">Open</a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="dash-card">
                    <h5 class="dash-card-title">Available Quizzes</h5>
                    <p class="dash-card-text">Attempt quizzes assigned to you.</p>
                    <a href="quiz_list.php" class="btn btn-success dash-btn">View</a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="dash-card">
                    <h5 class="dash-card-title">My Results</h5>
                    <p class="dash-card-text">Check your quiz performance.</p>
                    <a href="result.php" class="btn btn-info dash-btn">Check</a>
                </div>
            </div>

        </div>

        <div class="mt-4 text-center">
            <a href="logout.php" class="btn btn-danger dash-btn">Logout</a>
        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>
