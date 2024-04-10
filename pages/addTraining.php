<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training Attendance</title>
  <!-- Bootstrap CSS and Javascript-->
  <link rel="stylesheet" href="../css/css-bootstrap/bootstrap.min.css">
  <script src="../script/js-bootstrap/bootstrap.min.js"></script>
  <script src="../script/jquery-3.6.0.min.js"></script>
  <style>
    .input-group-text{
      width: 30%;
      flex-direction: column;
      align-items: end;
    }
  </style>
</head>
<body>
<header class="d-flex align-items-center py-2 px-3 sticky-top justify-content-between mb-4" style="background-color: #D0D0D0; height: 5rem;">
    <div>
      <img src="../src/img/csc-logo.png" alt="CSC Logo" width="60">
    </div>
    <div class="d-flex flex-column align-items-center">
      <a href="../index.php" class="text-dark d-flex flex-column align-items-center" style="text-decoration: none;">
        <h1>Training</h1>
        <h6>Attendance</h6>
      </a>
    </div>
    <div>
      <a class="btn" href="main.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
      </svg>
      </a>
    </div>
  </header>
  <div class="container text-center px-lg-5">
    <h1>Add Training</h1>
    <form action="../processes/addTrainingProcess.php" method="post" class="my-5">
      <div class="input-group mb-3">
        <label for="training-name" class="input-group-text">Name:</label>
        <input type="text" name="training-name" id="training-name" placeholder="Training Name" class="form-control" required>
      </div>
      <div class="input-group mb-3">
        <label for="training-month" class="input-group-text">Month:</label>
        <input type="text" name="training-month" id="training-month" placeholder="January" class="form-control" required><br>
      </div>
      <div class="input-group mb-3">
        <label for="training-year" class="input-group-text">Year:</label>
        <input type="number" name="training-year" id="training-year" placeholder="2024" class="form-control" required><br>
      </div>
      <div class="input-group mb-3">
        <label for="training-days" class="input-group-text">Days:</label>
        <input type="number" name="training-days" id="training-days" placeholder="3" class="form-control" required><br>
      </div>
      <input type="submit" value="Save training" class="btn btn-secondary">
    </form>
  </div>
</body>
</html>