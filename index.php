<?php
session_start();
include_once 'processes/db_connection.php';

if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: pages/main.php');
        exit();
    }
}
?>

<html>

<head>
    <link rel="icon" href="img/logo.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRD-TAS | Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="/icon/favicon.ico" rel="icon" type="image/png" />
</head>

<body>
    <form id="login-form" action="processes/login-process.php" method="post">
        <div class="csc-logo">

        </div>
        <?php
        if (isset($_GET['error'])) {
            $loginError = urldecode($_GET['error']);
            echo '<p class="login-error">' . $loginError . '</p>';
        }
        ?>
        <div class="input-container">
            <div class="img-container"><img src="src/img/username-logo.png" alt=""></div>
            <input type="text" id="username" name="username" placeholder="Enter Username" required>
        </div>
        <div class="input-container">
            <div class="img-container"><img src="src/img/password-logo.png" alt=""></div>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>
        </div>
        <input type="submit" value="Login">
    </form>
</body>

</html>