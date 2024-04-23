<?php
    // online mySql hosting at https://www.freemysqlhosting.net/

    $servername = "sql6.freemysqlhosting.net";
    $username = "sql6701161";
    $password = "3pQCsnk3BA";
    $database = "sql6701161";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>