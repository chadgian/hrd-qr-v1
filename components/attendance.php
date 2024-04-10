<?php
  include '../processes/db_connection.php';
?>
<div class="container d-flex flex-column justify-content-center align-items-center text-center mb-3">
  <h4><?php echo $trainingName; ?></h4>
  <hr style="width: 100%;" class="my-1">
  <h1 class="mb-0 pb-0">ATTENDANCE</h1>
  <h4><?php echo "Day $day"; ?></h4>
</div>

<div id="attendance-table" style="overflow: scroll; height: 100%;">
  <table class='table table-secondary table-striped' style="width: 100%;">
    <thead>
      <tr>
        <th class="text-center" style="position: sticky; top: 0;">Name</th>
        <th class="text-center" style="position: sticky; top: 0;">Agency</th>
        <th class="text-center" style="position: sticky; top: 0;">In</th>
        <th class="text-center" style="position: sticky; top: 0;">Out</th>
      </tr>
    </thead>
    <tbody id="table-body">
      <?php
        $trainingTable = "training-$trainingID-$day";

        $stmtAttendance = $conn->prepare("SELECT * FROM `$trainingTable`");
        $stmtAttendance->execute();
        $resultAttendance = $stmtAttendance->get_result();

        $latestLogin = 0;
        $latestLogout = 0;

        if($resultAttendance -> num_rows > 0){
            while ($data0 = $resultAttendance->fetch_assoc()){
                $logout = $data0['logout'];
                $login = $data0['login'];

                if ($login > $latestLogin){
                    $latestLogin = $login;
                }

                if ($logout > $latestLogout){
                    $latestLogout = $logout;
                }
            }
        }

        if ($latestLogin > $latestLogout){
            $stmt1 = $conn->prepare("SELECT * FROM `$trainingTable` ORDER BY login DESC");
        } else {
            $stmt1 = $conn->prepare("SELECT * FROM `$trainingTable` ORDER BY logout DESC");
        }

        if ($stmt1->execute()){
          $result1 = $stmt1->get_result();
          if($result1 -> num_rows > 0){
              while ($data1 = $result1->fetch_assoc()){
                  $lastname = $data1['lastname'];
                  $firstname = $data1['firstname'];
                  $middleinitial = $data1['middle_initial'];
                  $agency = $data1['agency'];
                  $login = ($data1['login'] != null) ? date("H:i", strtotime($data1['login'])) : "";
                  $logout = ($data1['logout'] != null) ? date("H:i", strtotime($data1['logout'])) : "";

                  echo "
                  <tr>
                    <td><div class='d-flex flex-column text-center'><p class='fw-bold my-0 py-0'>$lastname</p><p class='text-muted my-0 py-0'>$firstname $middleinitial</p></div></td>
                    <td class='text-center'>$agency</td>
                    <td class='text-center'>$login</td>
                    <td class='text-center'>$logout</td>
                  </tr>";
              }
          } else {
              echo "<i>No participants</i>";
          }
        } else {
            echo $stmt1->error;
        }
      ?>
    </tbody>
  </table>
</div>

<script>
  var element = document.getElementById("attendance-table");

  // Calculate the height from the element's position to the bottom of the screen
  var windowHeight = window.innerHeight;
  var elementTop = element.getBoundingClientRect().top;
  var heightFromTop = windowHeight - elementTop;

  // Set the calculated height to the element
  element.style.height = heightFromTop + "px";
</script>