<?php
session_start();
include("../config/db.php");

// Allow only admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch current settings (always 1 row)
$settingsQuery = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
$settings = mysqli_fetch_assoc($settingsQuery);

$message = "";

// Update settings
if (isset($_POST['update_settings'])) {

    $site_name  = mysqli_real_escape_string($conn, $_POST['site_name']);
    $site_desc  = mysqli_real_escape_string($conn, $_POST['site_description']);
    $allow_reg  = isset($_POST['allow_registration']) ? 1 : 0;
    $max_attempts = $_POST['max_attempts'];
    $show_score  = isset($_POST['show_score_immediately']) ? 1 : 0;

    $update = mysqli_query($conn, "
        UPDATE settings SET
            site_name = '$site_name',
            site_description = '$site_desc',
            allow_registration = $allow_reg,
            max_attempts = $max_attempts,
            show_score_immediately = $show_score
        WHERE id = {$settings['id']}
    ");

    if ($update) {
        $message = "Settings updated successfully!";
        // Reload updated values
        $settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings LIMIT 1"));
    } else {
        $message = "Failed to update settings.";
    }
}

include("../includes/header.php");
?>

<div class="container mt-4" style="max-width: 700px;">

    <h2 class="text-center mb-4">System Settings</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-info text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" class="p-4 shadow-sm admin-form-card">

        <!-- Site Name -->
        <div class="mb-3">
            <label class="form-label">Site Name</label>
            <input type="text" name="site_name" class="form-control"
                   value="<?php echo $settings['site_name']; ?>" required>
        </div>

        <!-- Site Description -->
        <div class="mb-3">
            <label class="form-label">Site Description</label>
            <textarea name="site_description" class="form-control" rows="3" required><?php 
                echo $settings['site_description']; ?></textarea>
        </div>

        <!-- Allow Registration -->
        <div class="form-check mb-3">
            <input type="checkbox" name="allow_registration" class="form-check-input"
                <?php echo ($settings['allow_registration'] == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label">Allow User Registration</label>
        </div>

        <!-- Max Attempts -->
        <div class="mb-3">
            <label class="form-label">Max Attempts Per Quiz</label>
            <input type="number" name="max_attempts" class="form-control" min="1"
                   value="<?php echo $settings['max_attempts']; ?>" required>
        </div>

        <!-- Show Score Immediately -->
        <div class="form-check mb-4">
            <input type="checkbox" name="show_score_immediately" class="form-check-input"
                <?php echo ($settings['show_score_immediately'] == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label">Show Score Immediately After Quiz</label>
        </div>

        <button type="submit" name="update_settings" class="btn btn-primary w-100">
            Save Settings
        </button>

        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-outline-secondary">Back to Admin Home</a>
        </div>

    </form>
</div>

<?php include("../includes/footer.php"); ?>
