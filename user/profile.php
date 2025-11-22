<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch user info
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($userQuery);

// Update profile
if (isset($_POST['update_profile'])) {
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

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
            $_SESSION['name'] = $name;
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

<div class="page-content d-flex justify-content-center">

    <div class="profile-container">

        <h2 class="text-center mb-4 profile-title">My Profile</h2>

        <?php if ($message != ""): ?>
            <div class="alert alert-info text-center mb-4"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Profile Update Card -->
        <div class="profile-card mb-4">
            <h4 class="profile-card-header">Update Profile</h4>

            <div class="profile-card-body">
                <form action="" method="POST">

                    <div class="mb-3">
                        <label class="form-label text-light">Full Name</label>
                        <input type="text" name="name" 
                               value="<?php echo $user['name']; ?>" 
                               class="form-control auth-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Email Address</label>
                        <input type="email" name="email" 
                               value="<?php echo $user['email']; ?>" 
                               class="form-control auth-input" required>
                    </div>

                    <button type="submit" name="update_profile" class="btn btn-primary w-100 btn-custom">
                        Update Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Password Update Card -->
        <div class="profile-card">
            <h4 class="profile-card-header">Change Password</h4>

            <div class="profile-card-body">
                <form action="" method="POST">

                    <div class="mb-3">
                        <label class="form-label text-light">Old Password</label>
                        <input type="password" name="old_password" class="form-control auth-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">New Password</label>
                        <input type="password" name="new_password" class="form-control auth-input" required minlength="6">
                    </div>

                    <button type="submit" name="update_password" class="btn btn-warning w-100 btn-custom">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>
