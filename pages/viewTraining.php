<?php
session_start();
include_once '../processes/db_connection.php';

if (isset($_SESSION['username'])) {
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $_SESSION['username']);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows <= 0) {
    header('Location: ../index.php');
    exit();
  }
} else {
  header('Location: ../index.php');
  exit();
}
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Training Attendance</title>
  <!-- Bootstrap CSS and Javascript-->
  <link rel="stylesheet" href="../css/css-bootstrap/bootstrap.min.css">
  <link href="../icon/favicon.ico" rel="icon" type="image/png" />
  <script src="../script/js-bootstrap/bootstrap.min.js"></script>
  <script src="../script/jquery-3.6.0.min.js"></script>
  <style>
    .input-group-text {
      width: 45%;
      flex-direction: column;
      align-items: end;
    }

    input[type="search"]::placeholder {
      color: white;
    }

    #sidebar a {
      text-decoration: none;
    }
  </style>
</head>

<body>
  <?php
  if (isset($_GET['id'])) {
    $trainingID = $_GET['id'];

    $stmtName = $conn->prepare("SELECT * FROM trainings WHERE training_id = ?");
    $stmtName->bind_param('s', $trainingID);
    $stmtName->execute();
    $resultName = $stmtName->get_result();
    if ($resultName->num_rows > 0) {
      $dataName = $resultName->fetch_assoc();
      $trainingName = $dataName['training_name'];
      $trainingDays = $dataName['training_days'];
    } else {
      $trainingName = "<i>Training Name not found</i>";
    }
  } else {
    // header('Location: index.php');
    // exit();
  }
  ?>
  <header class="d-flex align-items-center py-2 px-3 sticky-top justify-content-between mb-4"
    style="background-color: #D0D0D0; height: 5rem;">
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
      <a class="btn" data-bs-toggle="offcanvas" href="#sidebar" role="button" aria-controls="sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-list"
          viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
        </svg>
      </a>
    </div>
  </header>

  <!-- off canvas -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="sidebarLabel">Training Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="accordion" id="sidebarAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#addParticipant" aria-expanded="false" aria-controls="addParticipant">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-person-add me-2" viewBox="0 0 16 16">
                <path
                  d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                <path
                  d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
              </svg> Add Participants
            </button>
          </h2>
          <div id="addParticipant" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
              <p class="text-muted">Upload an excel file that contains the data of participants. The first column should
                contain the participant's number, followed by last name, first name, middle initial, and agency. <i>The
                  saving of data will start at 2nd row of the excel.</i></p>
              <form action="../processes/exportExcel.php" method="post" enctype="multipart/form-data"
                class="d-flex flex-column gap-3 justify-content-center align-items-center">
                <input type="hidden" value="<?php echo $trainingID; ?>" name="trainingID" id="trainingID">
                <input type="file" name="excelFile" id="excelFile" class="form-control">
                <input type="submit" value="Upload" class="btn btn-secondary">
              </form>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-person-gear me-2" viewBox="0 0 16 16">
                <path
                  d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0" />
              </svg>Edit Participants
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
              <form action="../processes/editParticipant.php" method='post'
                class="d-flex flex-column align-items-center justify-content-center">
                <p class="text-muted align-items-center">Leave field blank for unchanged data.</p>
                <input type="hidden" value="<?php echo $trainingID; ?>" name="trainingID" id="trainingID">
                <div class="input-group mb-3">
                  <label class="input-group-text" for="participantNo">Participant No.:</label>
                  <select name="participantNo" id="participantNo" class="form-select">
                    <option value="0">Add New</option>
                    <?php
                    $trainingTable = "training-$trainingID-1";
                    $stmtParticipants = $conn->prepare("SELECT * FROM `$trainingTable`");
                    $stmtParticipants->execute();
                    $resultParticipants = $stmtParticipants->get_result();
                    while ($row = $resultParticipants->fetch_assoc()) {
                      $participantID = $row['participant_id'];
                      $participantName = $row['firstname'] . " " . $row['middle_initial'] . " " . $row['lastname'];
                      echo "<option value=$participantID>$participantID. $participantName</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="input-group mb-3">
                  <label for="firstname" class="input-group-text">First Name:</label>
                  <input type="text" name="firstname" id="firstname" placeholder="e.g. Juan" class="form-control">
                </div>
                <div class="input-group mb-3">
                  <label for="middle_initial" class="input-group-text">Middle Inital:</label>
                  <input type="text" name="middle_initial" id="middle_initial" class="form-control"
                    placeholder="e.g. G.">
                </div>
                <div class="input-group mb-3">
                  <label for="lastname" class="input-group-text">Last Name:</label>
                  <input type="text" name="lastname" id="lastname" class="form-control" placeholder="e.g. Dela Cruz">
                </div>
                <div class="input-group mb-3">
                  <label for="agency" class="input-group-text">Agency:</label>
                  <input type="text" name="agency" id="agency" class="form-control" placeholder="e.g. CSC RO6">
                </div>
                <input type="submit" value="Save" class="btn btn-secondary">
              </form>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-person-badge me-2" viewBox="0 0 16 16">
                <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                <path
                  d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z" />
              </svg>Generate ID
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body">
              <form action="../processes/generateIDProcess.php" method="POST"
                class="d-flex flex-column align-items-center justify-content-center">
                <input type="hidden" value="<?php echo $trainingID; ?>" name='id' id='id'>
                <div class="input-group mb-3">
                  <label for="trainingName" class="input-group-text">Training Name:</label>
                  <input type="text" name="trainingName" id="trainingName" class="form-control"
                    placeholder="e.g. ePRIMEtime" required>
                </div>
                <div class="input-group mb-3">
                  <label for="trainingDate" class="input-group-text">Training Days:</label>
                  <input type="text" name="trainingDate" id="trainingDate" class="form-control"
                    placeholder="e.g. April 4-5, 2024" required>
                </div>
                <div class="input-group mb-3">
                  <label for="trainingVenue" class="input-group-text">Training Venue:</label>
                  <input type="text" name="trainingVenue" id="trainingVenue" class="form-control"
                    placeholder="e.g. Grand Xing Imperial Hotel, Iloilo City" required>
                </div>
                <input type="submit" value="Generate" id="submit-button" style="display: block;"
                  class="btn btn-secondary">
              </form>
              <div class="text-center">
                <?php
                $id = $trainingID;
                $trainingTable = "training-$id-1";

                try {
                  $stmt1 = $conn->prepare("SELECT * FROM `$trainingTable`");
                  $stmt1->execute();
                  $result = $stmt1->get_result();

                  $totalParticipants = $result->num_rows;
                } catch (Exception $e) {

                }

                $trainingIDFolder = "../generated_ids/training-$id/";

                $jpgCounter = 0;

                if (!file_exists($trainingIDFolder)) {
                  mkdir($trainingIDFolder, 0777, true);
                }

                $files = scandir($trainingIDFolder);

                // Loop through each item in the files array
                foreach ($files as $file) {
                  // Exclude "." and ".." which represent the current and parent directory
                  if ($file != "." && $file != "..") {
                    // Check if the current item is a file (not a directory) and ends with ".jpg"
                    // && pathinfo($file, PATHINFO_EXTENSION) === 'jpg'
                    if (is_file($trainingIDFolder . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'jpg') {
                      $jpgCounter++;
                    }
                  }
                }

                if (!file_exists("../generated_ids/training-$id.docx")) {
                  if ($jpgCounter == $totalParticipants) {
                    require_once "../vendor/autoload.php";
                    $phpWord = new \PhpOffice\PhpWord\PhpWord();
                    $width = 3.74 * 72;
                    $height = 2.38 * 72;

                    $files = scandir($trainingIDFolder);

                    $section = $phpWord->addSection();


                    // Loop through each item in the files array
                    foreach ($files as $file) {
                      // Exclude "." and ".." which represent the current and parent directory
                      if ($file != "." && $file != "..") {
                        // Check if the current item is a file (not a directory) and ends with ".jpg"
                        // && pathinfo($file, PATHINFO_EXTENSION) === 'jpg'
                        if (is_file($trainingIDFolder . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'jpg') {
                          // Increment the JPG file counter
                          $filepath = "$trainingIDFolder$file";
                          // echo "<img src='$filepath'> <br><hr>";
                          $section->addImage($filepath, array('width' => $width, 'height' => $height));//, 'wrappingStyle' => 'inline', 'positioning' => 'relative'
                        }
                      }
                    }

                    $filename = "training-$id.docx";
                    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                    $objWriter->save("../generated_ids/$filename");

                  } else {
                    echo "<div id='id-found'><i>No ID found for this training. Generate IDs first!</i></div>";
                  }
                } else {
                  echo "<div id='id-found'><i>Generated IDs found. <a href='../generated_ids/training-$id.docx' download>Download</a></i></div>";
                }

                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-card-checklist me-2" viewBox="0 0 16 16">
                <path
                  d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                <path
                  d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
              </svg>View Attendance
            </button>
          </h2>
          <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body d-flex justify-content-around align-items-start gap-2">
              <?php
              for ($i = 1; $i <= $trainingDays; $i++) {
                echo "<a class='btn btn-secondary' href='viewTraining.php?page=attendance&day=$i&id=$trainingID' class='text-dark fw-bold' style='text-decoration: none;'>Day $i</a>";
              }
              ?>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-qr-code-scan me-2" viewBox="0 0 16 16">
                <path
                  d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z" />
                <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z" />
                <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z" />
                <path
                  d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z" />
                <path d="M12 9h2V8h-2z" />
              </svg>Scan Attendance
            </button>
          </h2>
          <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body d-flex flex-column justify-content-start align-items-start gap-2">
              <h6><?php echo $trainingName; ?></h6>
              <div class='accordion' id='attendanceAccordion' style="width: 100%;">
                <?php
                for ($i = 1; $i <= $trainingDays; $i++) {
                  echo "
                      <div class='accordion-item'>
                        <h2 class='accordion-header'>
                          <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#out-$i' aria-expanded='false' aria-controls='out-$i'>
                            Day $i
                          </button>
                        </h2>
                        <div id='out-$i' class='accordion-collapse collapse' data-bs-parent='#attendanceAccordion'>
                          <div class='accordion-body d-flex flex-column justify-content-start align-items-start gap-2'>
                            <form action='scan.php' method='post' class='d-flex align-items-center justify-content-around' style='width: 100%;'>
                              <input type='hidden' value='$trainingID' id='training' name='training'>
                              <input type='hidden' value='$i' id='days' name='days'>
                              <input type='submit' value='in' id='inORout' name='inORout' style='width: 25%;' class='btn btn-secondary'>
                              <input type='submit' value='out' id='inORout' name='inORout' style='width: 25%;' class='btn btn-secondary'>
                            </form>
                          </div>
                        </div>
                      </div>
                    ";
                }
                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-card-list me-2" viewBox="0 0 16 16">
                <path
                  d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                <path
                  d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
              </svg>Edit Attendance
            </button>
          </h2>
          <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#sidebarAccordion">
            <div class="accordion-body d-flex justify-content-around align-items-start gap-2">
              <form action="../processes/editAttendance.php" method='post'
                class="d-flex flex-column align-items-center justify-content-center">
                <div class="input-group mb-3">
                  <label for="participantNo" class="input-group-text">Participant: </label>
                  <select name="participantNo" id="participantNo" class="form-select">
                    <?php
                    $trainingTable = "training-$trainingID-1";
                    $stmtParticipants = $conn->prepare("SELECT * FROM `$trainingTable`");
                    $stmtParticipants->execute();
                    $resultParticipants = $stmtParticipants->get_result();
                    while ($row = $resultParticipants->fetch_assoc()) {
                      $participantID = $row['participant_id'];
                      $participantName = $row['firstname'] . " " . $row['middle_initial'] . " " . $row['lastname'];
                      echo "<option value=$participantID>$participantID. $participantName</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="input-group mb-3">
                  <label for="days" class="input-group-text">Day:</label>
                  <select name="days" id="days" class="form-select">
                    <?php
                    $stmtDays = $conn->prepare("SELECT * FROM trainings WHERE training_id = ?");
                    $stmtDays->bind_param("s", $trainingID);
                    $stmtDays->execute();
                    $resultDays = $stmtDays->get_result();
                    $rowDays = $resultDays->fetch_assoc();
                    $trainingDuration = $rowDays['training_days'];

                    for ($i = 0; $i < $trainingDuration; $i++) {
                      $day = $i + 1;
                      echo "<option value='$day'>$day</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="input-group mb-3">
                  <label for="inORout" class="input-group-text">In or Out:</label>
                  <select name="inORout" id="inORout" class="form-select">
                    <option value="in">Sign In</option>
                    <option value="out">Sign Out</option>
                  </select>
                </div>
                <div class="input-group mb-3">
                  <label for="time" class="input-group-text">Time:</label>
                  <input type="time" id="time" name="time" class="form-control">
                </div>
                <?php
                echo "<input type='hidden' value='$trainingID' name='trainingID' id='trainingID'>";
                ?>
                <input class="btn btn-secondary" type="submit" value="Save">
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex flex-column justify-content-center align-items-center bottom my-3">

        <a href="../processes/exportAttendance.php?id=<?php echo $trainingID; ?>" class='text-dark my-3'>Export
          Attendance</a>
        <!-- Button trigger modal -->
        <a class="text-dark" data-bs-toggle="modal" data-bs-target="#exampleModal" style="cursor: pointer;">
          Delete Training
        </a>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Training</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Are you sure to delete the training "<b><?php echo $trainingName; ?></b>"? This action cannot be undone.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger"><a
                    href="../processes/deleteTraining.php?id=<?php echo $trainingID; ?>" class="text-light"
                    style="text-decoration: none;">DELETE</a></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <?php
  $page = $_GET['page'] ?? null;

  $trainingID = $_GET['id'];

  switch ($page) {
    case "attendance":
      $day = $_GET['day'];
      require "../components/attendance.php";
      break;

    // Add more cases for other pages here
    default:
      require "../components/participants.php";
      break;
  }

  ?>

</body>

</html>