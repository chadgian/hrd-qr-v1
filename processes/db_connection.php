<?php
    $servername = "localhost";
    $username = "root";
    $password = ""; // This might be empty or your MySQL root password
    $database = "qr_attendance_db";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>