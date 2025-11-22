<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

$message = "";

if (isset($_POST['register'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = mysqli_real_escape_string($conn, $_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $message = "Email already registered!";
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        $insert = mysqli_query($conn,
            "INSERT INTO users (name, email, password, role, created_at)
             VALUES ('$name', '$email', '$hashed', 'user', NOW())"
        );

        if ($insert) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $message = "Registration failed. Try again!";
        }
    }
}
?>

<?php include("../includes/header.php"); ?>

<div class="page-content d-flex justify-content-center align-items-center">

    <div class="auth-card">

        <h2 class="text-center mb-4 auth-title">Create an Account</h2>

        <?php if ($message != ""): ?>
            <div class="alert alert-danger text-center mb-3"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">

            <div class="mb-3">
                <label class="form-label text-light">Full Name</label>
                <input type="text" name="name" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Email Address</label>
                <input type="email" name="email" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Password</label>
                <input type="password" name="password" class="form-control auth-input" required minlength="6">
            </div>

            <button type="submit" name="register" class="btn btn-primary w-100 btn-custom">
                Register
            </button>

            <p class="text-center mt-3 text-light">
                Already have an account?
                <a href="login.php" class="auth-link">Login here</a>
            </p>

        </form>
    </div>

</div>

<?php include("../includes/footer.php"); ?>
