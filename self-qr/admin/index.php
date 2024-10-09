<?php
include '../../processes/db_connection.php';

$selfAttendanceDetails = $conn->prepare("SELECT * FROM `_self-attendance-details`");
$selfAttendanceDetails->execute();
$result = $selfAttendanceDetails->get_result();

$selfAttendance = [];

while ($selfAttendanceData = $result->fetch_assoc()) {
  $selfAttendance[$selfAttendanceData['detailName']] = $selfAttendanceData['detailData'];
}

$usernames = ["day1login" => "", "day1logout" => "", "day2login" => "", "day2logout" => ""];

foreach ($usernames as $username => $password) {
  $getPassword = $conn->prepare("SELECT * FROM `_self-attendance-user` WHERE username = ?");
  $getPassword->bind_param("s", $username);
  if ($getPassword->execute()) {
    $getPasswordResult = $getPassword->get_result();
    $getPasswordData = $getPasswordResult->fetch_assoc();

    $usernames[$username] = $getPasswordData['password'];
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
  <link rel="stylesheet" href="style.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <style>
    .showPassword {
      font-family: 'Courier New', serif;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <h2>ADMIN</h2>
  <h5>Self-Attendance Details</h5>
  <div class="detailsCon">
    <form action="updateSelfAttendance.php" method="POST" class="d-flex flex-column justify-content-center">
      <select name="training" id="training" class="form-control">
        <?php
        $getAllTraining = $conn->prepare("SELECT * FROM trainings ORDER BY training_id DESC");
        $getAllTraining->execute();
        $getAllTrainingResult = $getAllTraining->get_result();
        while ($getAllTrainingData = $getAllTrainingResult->fetch_assoc()) {
          $allTrainingID = $getAllTrainingData['training_id'];
          $allTrainingName = $getAllTrainingData['training_name'];

          if ($allTrainingID == $selfAttendance['trainingID']) {
            echo "<option value='$allTrainingID' selected>$allTrainingName</option>";
          } else {
            echo "<option value='$allTrainingID'>$allTrainingName</option>";
          }
        }
        ?>
      </select>
      <div class="dayCon">
        <div class="row g-3 align-items-center mb-3">
          <div class="col-auto">
            <label for="day1date" class="form-label h4">Day 1 Date:</label>
          </div>
          <div class="col-auto">
            <input type="date" id="day1date" name="day1date" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day1date']))->format("Y-m-d"); ?>">
          </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
          <div class="col-auto">
            <span>Login:</span>
          </div>
          <div class="col-auto">
            <input type="time" id="day1loginStart" name="day1loginStart" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day1loginStartTime']))->format("H:i:s"); ?>">
          </div>
          <div class="col-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right"
              viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg>
          </div>
          <div class="col-auto">
            <input type="time" id="day1loginEnd" name="day1loginEnd" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day1loginEndTime']))->format("H:i:s"); ?>">
          </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
          <div class="col-auto">
            <span>Logout:</span>
          </div>
          <div class="col-auto">
            <input type="time" id="day1logoutStart" name="day1logoutStart" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day1logoutStartTime']))->format("H:i:s"); ?>">
          </div>
          <div class="col-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right"
              viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg>
          </div>
          <div class="col-auto">
            <input type="time" id="day1logoutEnd" name="day1logoutEnd" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day1logoutEndTime']))->format("H:i:s"); ?>">
          </div>
        </div>
        <div class="text-center col-auto text-decoration-underline" style="cursor: pointer;" onclick="showPassword(1)">
          <small>show passwords</small>
        </div>
      </div>
      <div class="dayCon">
        <div class="row g-3 align-items-center  mb-3">
          <div class="col-auto">
            <label for="day2date" class="form-label h4">Day 2 Date:</label>
          </div>
          <div class="col-auto">
            <input type="date" id="day2date" name="day2date" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day2date']))->format("Y-m-d"); ?>">
          </div>
        </div>
        <div class=" row g-3 align-items-center mb-3">
          <div class="col-auto">
            <span>Login:</span>
          </div>
          <div class="col-auto">
            <input type="time" id="day2loginStart" name="day2loginStart" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day2loginStartTime']))->format("H:i:s"); ?>">
          </div>
          <div class="col-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right"
              viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg>
          </div>
          <div class="col-auto">
            <input type="time" id="day2loginEnd" name="day2loginEnd" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day2loginEndTime']))->format("H:i:s"); ?>">
          </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
          <div class="col-auto">
            <span>Logout:</span>
          </div>
          <div class="col-auto">
            <input type="time" id="day2logoutStart" name="day2logoutStart" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day2logoutStartTime']))->format("H:i:s"); ?>">
          </div>
          <div class="col-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right"
              viewBox="0 0 16 16">
              <path fill-rule="evenodd"
                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg>
          </div>
          <div class="col-auto">
            <input type="time" id="day2logoutEnd" name="day2logoutEnd" class="form-control"
              value="<?php echo (new DateTime($selfAttendance['day2logoutEndTime']))->format("H:i:s"); ?>">
          </div>
        </div>
        <div class="text-center col-auto text-decoration-underline" style="cursor: pointer;" onclick="showPassword(1)">
          <small>show passwords</small>
        </div>
      </div>
      <button type="submit" class="btn btn-success">SAVE</button>
    </form>
  </div>
  <a href="../logout.php" class="btn btn-danger">LOGOUT</a>

  <div class="modal fade" id="showPassword" tabindex="-1" aria-labelledby="showPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="showPasswordLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div>
            <label for="loginPassword" class="form-label">Login Password:</label>
            <input type="text" id="loginPassword" name="loginPassword" class="form-control showPassword" disabled>
          </div>
          <div>
            <label for="loginPassword" class="form-label">Logout Password:</label>
            <input type="text" id="logoutPassword" name="logoutPassword" class="form-control showPassword" disabled>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>

  <script>
    function showPassword(day) {

      var loginPassword;
      var logoutPassword;

      switch (day) {
        case 1:
          loginPassword = "hgM2i4cF";
          logoutPassword = "oJDswoiS";
          $("#loginPassword").val("<?php echo $usernames['day1login']; ?>");
          $("#logoutPassword").val("<?php echo $usernames['day1logout']; ?>");
          $("#showPasswordLabel").html("Day 1 Password");
          break;

        case 2:
          loginPassword = "bZmhRxIN";
          logoutPassword = "l9SyHHRV";
          $("#loginPassword").val("<?php echo $usernames['day2login']; ?>");
          $("#logoutPassword").val("<?php echo $usernames['day2logout']; ?>");
          $("#showPasswordLabel").html("Day 2 Password");
          break;

        default:
          break;
      }

      $("#showPassword").modal("toggle");


    }
  </script>
</body>

</html>