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

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Email already registered!";
    } else {
        // Hash password
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        // Insert into DB
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

<div class="container mt-5" style="max-width: 500px;">

    <h2 class="text-center mb-4">Create an Account</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="border p-4 rounded bg-light shadow-sm">

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <input value="Register" type="submit" name="register" class="btn btn-primary w-100">

        <p class="text-center mt-3">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </form>
</div>

<?php include("../includes/footer.php"); ?>
