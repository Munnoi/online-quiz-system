<?php
if (!isset($_SESSION)) {
    session_start();
}

$role = $_SESSION['role'] ?? 'guest';
$is_admin_page = strpos($_SERVER['REQUEST_URI'], '/quiz-app/admin/') !== false;
?>

<link rel="stylesheet" href="/quiz-app/assets/css/navbar.css">

<nav class="custom-navbar navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold d-flex align-items-center brand-glow"
      href="<?php
            if ($role === 'admin') {
                echo '/quiz-app/admin/';
            } else if ($is_admin_page) {
                echo '/quiz-app/admin/';
            } else if ($role === 'user') {
                echo '/quiz-app/user/';
            } else {
                echo '/quiz-app/auth/login.php';
            }
        ?>">
        <span class="brand-icon me-2">📚</span>
        Online Quiz System
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <?php if ($role === 'admin'): ?>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/admin/view_quizzes.php">Quizzes</a>
          </li>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/admin/manage_users.php">Users</a>
          </li>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/admin/view_results.php">Results</a>
          </li>

        <?php elseif ($role === 'user'): ?>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/user/quiz_list.php">Quizzes</a>
          </li>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/user/history.php">History</a>
          </li>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/user/result.php">Results</a>
          </li>

        <?php endif; ?>


        <?php if ($role === 'guest'): ?>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/auth/login.php">Login</a>
          </li>

          <li class="nav-item">
            <a class="nav-link nav-item-anim" href="/quiz-app/auth/register.php">Register</a>
          </li>

        <?php else: ?>

          <li class="nav-item">
            <a class="nav-link nav-item-anim logout-link" href="/quiz-app/auth/logout.php">Logout</a>
          </li>

        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
