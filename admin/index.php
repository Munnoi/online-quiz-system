<?php
session_start();
include("../config/db.php");

$message = "";

// If admin tries to login
if (isset($_POST['admin_login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        if ($user['role'] !== 'admin') {
            $message = "Access denied! You are not an admin.";
        }
        else if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['name']    = $user['name'];
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Admin not found!";
    }
}

include("../includes/header.php");

// IF ADMIN IS LOGGED IN → SHOW ADMIN HOME PAGE
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Welcome Admin</h2>

    <div class="alert alert-success text-center">
        Logged in as: <strong><?php echo $_SESSION['name']; ?></strong>
    </div>

    <div class="row">

        <div class="col-md-4">
            <a href="view_quizzes.php" class="btn btn-primary w-100 mb-3">Manage Quizzes</a>
        </div>

        <div class="col-md-4">
            <a href="view_questions.php" class="btn btn-secondary w-100 mb-3">Manage Questions</a>
        </div>

        <div class="col-md-4">
            <a href="view_results.php" class="btn btn-info w-100 mb-3">View Results</a>
        </div>

        <div class="col-md-4">
            <a href="manage_users.php" class="btn btn-dark w-100 mb-3">Manage Users</a>
        </div>

        <div class="col-md-4">
            <a href="logout.php" class="btn btn-danger w-100 mb-3">Logout</a>
        </div>

    </div>

</div>

<?php 
include("../includes/footer.php");
exit;
endif;
?>

<!-- OTHERWISE SHOW ADMIN LOGIN FORM -->

<div class="container mt-5" style="max-width: 450px;">
    <h2 class="text-center mb-4 text-primary">Admin Login</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="border p-4 rounded bg-light shadow-sm">

        <div class="mb-3">
            <label class="form-label">Admin Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Admin Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="admin_login" class="btn btn-primary w-100">
            Login as Admin
        </button>

    </form>
</div>

<?php include("../includes/footer.php"); ?>
