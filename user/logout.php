<?php
session_start();

// Destroy everything
session_unset();
session_destroy();

// Prevent back button after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

// Redirect to login page
header("Location: ../auth/login.php");
exit;
?>
