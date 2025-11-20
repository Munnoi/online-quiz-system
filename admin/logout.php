<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Prevent back button access
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

// Redirect to admin login
header("Location: index.php");
exit;
?>
