<?php
session_start();
include("../config/db.php");

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch current user data
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($userQuery);

// Update profile
if (isset($_POST['update_profile'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email belongs to someone else
    $checkEmail = mysqli_query($conn, 
        "SELECT * FROM users WHERE email='$email' AND user_id != $user_id"
    );

    if (mysqli_num_rows($checkEmail) > 0) {
        $message = "Email already taken by another user!";
    } else {
        $update = mysqli_query($conn,
            "UPDATE users SET name='$name', email='$email' WHERE user_id=$user_id"
        );

        if ($update) {
            $_SESSION['name'] = $name; // update session name
            $message = "Profile updated successfully!";
        } else {
            $message = "Failed to update profile!";
        }
    }
}

// Update password
if (isset($_POST['update_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];

    // Check old password
    if (!password_verify($old_pass, $user['password'])) {
        $message = "Old password is incorrect!";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

        $updatePass = mysqli_query($conn,
            "UPDATE users SET password='$hashed' WHERE user_id=$user_id"
        );

        if ($updatePass) {
            $message = "Password updated successfully!";
        } else {
            $message = "Failed to update password!";
        }
    }
}

?>

<?php include("../includes/header.php"); ?>

<div class="container mt-4" style="max-width: 600px;">

    <h2 class="mb-4 text-center">My Profile</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Profile Update Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Update Profile
        </div>
        <div class="card-body">
            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="<?php echo $user['name']; ?>" 
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" 
                           class="form-control" required>
                </div>

                <button type="submit" name="update_profile" class="btn btn-success w-100">
                    Update Profile
                </button>
            </form>
        </div>
    </div>

    <!-- Password Update Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Change Password
        </div>
        <div class="card-body">
            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label">Old Password</label>
                    <input type="password" name="old_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required minlength="6">
                </div>

                <button type="submit" name="update_password" class="btn btn-warning w-100">
                    Update Password
                </button>
            </form>
        </div>
    </div>

</div>

<?php include("../includes/footer.php"); ?>
