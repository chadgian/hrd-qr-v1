<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    :root {
      --bs-body-bg: #bad5e9;
    }

    .login-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .login-form {
      background-color: #739ebd;
      border-radius: 5px;
      color: black;
    }
  </style>
</head>

<body>
  <img src="../img/header.png" alt="" class="img-fluid">
  <div class="login-container container-sm mt-5" id="login-container">
    <span class="fs-4 fw-bolder" style="color: #013378;">LOGIN AS ADMIN</span>
    <span class="fs-5 fw-bolder" style="color: #013378;">2024 REGIONAL HR LEADERS SUMMIT</span>
    <form action="loginProcess.php" method="GET"
      class="login-form mt-3 py-3 px-5 d-flex flex-column justify-content-center align-items-center">
      <input type="hidden" name="username" id="username" value="admin">
      <div class="d-flex flex-column justify-content-center align-items-center">
        <label for="password" class="fw-bold fs-4">PASSWORD</label>
        <?php
        if (isset($_GET['err'])) {
          echo "<small style='color: red; font-style: italic;'>error password</small>";
        }

        ?>
        <input type="password" name="password" id="password" class="form-control my-2 text-center bg-white"
          placeholder="Type the password here...">
        <small class="mb-3 text-decoration-underline" style="cursor: pointer;" onclick="passwordToggle()"
          id="showPasswordElement">show
          password</small>
        <button type="submit" class="btn btn-primary fw-bolder">ENTER</button>
      </div>
    </form>
  </div>
  <div class="d-flex justify-content-center align-items-center mt-3">
    <a href="../login.php">Login as participant</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>

  <script>
    function passwordToggle() {
      var x = document.getElementById("password");
      if (x.type === "password") {
        $("#showPasswordElement").html("hide password");
        x.type = "text";
      } else {
        x.type = "password";
        $("#showPasswordElement").html("show password");
      }
    }
  </script>

</body>

</html>