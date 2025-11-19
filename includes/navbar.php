<?php
if (!isset($_SESSION)) {
    session_start();
}

// Default role if not logged in
$role = $_SESSION['role'] ?? 'guest';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <a class="navbar-brand" 
       href="<?php echo ($role == 'admin') 
         ? '/quiz-app/admin/' 
         : '/quiz-app/user/'; ?>">
      Online Quiz System
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">

        <?php if ($role == 'admin'): ?>

          <!-- Admin Links -->
          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/admin/view_quizzes.php">Quizzes</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/admin/view_questions.php">Questions</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/admin/manage_users.php">Users</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/admin/view_results.php">Results</a>
          </li>

        <?php elseif ($role == 'user'): ?>

          <!-- User Links -->
          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/user/quiz_list.php">Quizzes</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/user/history.php">History</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/user/result.php">Results</a>
          </li>

        <?php endif; ?>

        <?php if ($role == 'guest'): ?>
          <!-- Guest Links -->
          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/auth/login.php">Login</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/quiz-app/auth/register.php">Register</a>
          </li>
        <?php else: ?>
          <!-- Logout -->
          <li class="nav-item">
            <a class="nav-link text-danger" href="/quiz-app/auth/logout.php">Logout</a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
