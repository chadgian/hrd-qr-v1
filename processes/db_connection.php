<?php
    // online mySql hosting at https://www.freemysqlhosting.net/

    $servername = "sql6.freemysqlhosting.net";
    $username = "sql6701218";
    $password = "6W1lbwlUCR";
    $database = "sql6701218";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>