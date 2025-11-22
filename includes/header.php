<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz System</title>

    <!-- Favicon -->
    <link rel="icon" href="/quiz-app/assets/img/favicon.png" type="image/png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <!-- Global Styles -->
    <link rel="stylesheet" href="/quiz-app/assets/css/global.css">

    <!-- Navbar Styles -->
    <link rel="stylesheet" href="/quiz-app/assets/css/navbar.css">

</head>
<body class="app-body">

<?php include("navbar.php"); ?>
