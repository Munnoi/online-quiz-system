<?php
session_start();
include("../config/db.php");

$message = "";
$registered_success = isset($_GET['registered']);

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 1) {
        $row = mysqli_fetch_assoc($query);

        if (password_verify($pass, $row['password'])) {

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role']    = $row['role'];
            $_SESSION['name']    = $row['name'];

            if ($row['role'] == 'admin') {
                header("Location: ../admin");
                exit;
            } else {
                header("Location: ../user");
                exit;
            }

        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Email not found!";
    }
}
?>

<?php include("../includes/header.php"); ?>

<div class="page-content d-flex justify-content-center align-items-center">

    <div class="auth-card">
        <h2 class="text-center mb-4 auth-title">Login</h2>

        <?php if ($registered_success): ?>
            <div class="alert alert-success text-center mb-3">Registration successful! Please login.</div>
        <?php endif; ?>

        <?php if ($message != ""): ?>
            <div class="alert alert-danger text-center mb-3"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">

            <div class="mb-3">
                <label class="form-label text-light">Email Address</label>
                <input type="email" name="email" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Password</label>
                <input type="password" name="password" class="form-control auth-input" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100 btn-custom">
                Login
            </button>

            <p class="text-center mt-3 text-light">
                Don't have an account?
                <a href="register.php" class="auth-link">Register here</a>
            </p>

        </form>
    </div>

</div>

<?php include("../includes/footer.php"); ?>
