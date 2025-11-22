<?php
session_start();
include("../config/db.php");

$message = "";

// Handle admin login
if (isset($_POST['admin_login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        if ($user['role'] !== 'admin') {
            $message = "Access denied! You are not an admin.";
        } elseif (password_verify($pass, $user['password'])) {
            // Successful admin login
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['name']    = $user['name'];

            // Refresh to show dashboard immediately (prevents form resubmission)
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Admin not found!";
    }
}

// Handle optional logout from this same file (if called with ?action=logout)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Clear session and redirect to this page (shows login)
    session_unset();
    session_destroy();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

include("../includes/header.php");
?>

<div class="page-content">

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

    <!-- ADMIN DASHBOARD (when logged in) -->
    <div class="dashboard-container" style="max-width:1100px; margin:auto; padding:2rem;">
        <h2 class="dashboard-title text-center mb-3">Welcome, Admin 👋</h2>

        <p class="dashboard-subtitle text-center mb-4">
            Logged in as: <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>
        </p>

        <div class="row g-3">

            <div class="col-md-4">
                <div class="dash-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="dash-card-title">Manage Quizzes</h5>
                        <p class="dash-card-text">Create, edit or remove quizzes for users.</p>
                    </div>
                    <a href="view_quizzes.php" class="btn btn-primary dash-btn mt-3">Open</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dash-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="dash-card-title">Manage Questions</h5>
                        <p class="dash-card-text">Add or modify quiz questions.</p>
                    </div>
                    <a href="view_questions.php" class="btn btn-secondary dash-btn mt-3">Open</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dash-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="dash-card-title">View Results</h5>
                        <p class="dash-card-text">Inspect users' quiz attempts and scores.</p>
                    </div>
                    <a href="view_results.php" class="btn btn-info dash-btn mt-3">Open</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dash-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="dash-card-title">Manage Users</h5>
                        <p class="dash-card-text">Promote, demote, or edit user accounts.</p>
                    </div>
                    <a href="manage_users.php" class="btn btn-dark dash-btn mt-3">Open</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dash-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="dash-card-title">Site Settings</h5>
                        <p class="dash-card-text">(Optional) Configure site-wide settings.</p>
                    </div>
                    <a href="settings.php" class="btn btn-outline-light dash-btn mt-3">Open</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dash-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="dash-card-title">Logout</h5>
                        <p class="dash-card-text">Sign out of the admin session.</p>
                    </div>
                    <a href="?action=logout" class="btn btn-danger dash-btn mt-3">Logout</a>
                </div>
            </div>

        </div>
    </div>

<?php else: ?>

    <!-- ADMIN LOGIN (when not logged in) -->
    <div class="d-flex justify-content-center align-items-center" style="min-height:60vh; padding:2rem;">
        <div class="auth-card" style="max-width:480px; width:100%;">
            <h2 class="text-center mb-3 auth-title">Admin Login</h2>

            <?php if ($message != ""): ?>
                <div class="alert alert-danger text-center"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form action="" method="POST" autocomplete="off" novalidate>
                <div class="mb-3">
                    <label class="form-label text-light">Admin Email</label>
                    <input type="email" name="email" class="form-control auth-input" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-light">Admin Password</label>
                    <input type="password" name="password" class="form-control auth-input" required>
                </div>

                <button type="submit" name="admin_login" class="btn btn-primary w-100 btn-custom">
                    Login as Admin
                </button>
            </form>

            <p class="text-center mt-3 text-light small">
                Note: Only users with the <code>admin</code> role can log in here.
            </p>
        </div>
    </div>

<?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
