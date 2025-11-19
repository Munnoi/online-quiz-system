<?php
session_start();
include("../config/db.php");

$message = "";

// Check if user came from register
$registered_success = isset($_GET['registered']);

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if email exists
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 1) {
        $row = mysqli_fetch_assoc($query);

        // Verify password
        if (password_verify($pass, $row['password'])) {

            // Store session values
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role']    = $row['role'];
            $_SESSION['name']    = $row['name'];

            // Redirect based on role
            if ($row['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                header("Location: ../user/dashboard.php");
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

<div class="container mt-5" style="max-width: 450px;">

    <h2 class="text-center mb-4">Login</h2>

    <!-- Registration success alert -->
    <?php if ($registered_success): ?>
        <div class="alert alert-success">Registration successful! Please login.</div>
    <?php endif; ?>

    <!-- Error message -->
    <?php if ($message != ""): ?>
        <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="border p-4 bg-light rounded shadow-sm">

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100">
            Login
        </button>

        <p class="text-center mt-3">
            Don't have an account?
            <a href="register.php">Register here</a>
        </p>

    </form>
</div>

<?php include("../includes/footer.php"); ?>
