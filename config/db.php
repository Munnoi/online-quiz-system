<?php
    $host = "localhost";
    $username = "root";
    $pass = "";
    $dbname = "quiz";

    $conn = mysqli_connect($host, $username, $pass, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>