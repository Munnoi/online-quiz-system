<?php include("includes/header.php"); ?>

<div class="page-content d-flex justify-content-center align-items-center">
    
    <div class="hero-container text-center">

        <div class="hero-icon mb-4">📚</div>

        <h1 class="hero-title">Online Quiz System</h1>
        <p class="hero-subtitle">Test your knowledge. Track your progress. Learn smarter.</p>

        <div class="hero-buttons mt-4">

            <?php if (!isset($_SESSION['role']) || $_SESSION['role'] === 'guest'): ?>
                <a href="auth/login.php" class="btn btn-primary btn-lg hero-btn me-2">Login</a>
                <a href="auth/register.php" class="btn btn-outline-light btn-lg hero-btn">Register</a>
            <?php elseif ($_SESSION['role'] === 'admin'): ?>
                <a href="admin/" class="btn btn-primary btn-lg hero-btn">Go to Admin Dashboard</a>
            <?php else: ?>
                <a href="user/" class="btn btn-primary btn-lg hero-btn">Go to User Dashboard</a>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php include("includes/footer.php"); ?>
