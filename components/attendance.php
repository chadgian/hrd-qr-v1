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

  $(document).ready(function(){
  // Function to fetch and display data from the server
  var trainingID = <?php echo json_encode($trainingID); ?>;
  var day = <?php echo json_encode($day); ?>;

  function fetchData() {
      $.ajax({
          url: '../processes/fetchAttendance.php',
          type: 'GET',
          data:{
              id: trainingID, 
              days: day
          },
          success: function(data) {
              $('#attendance-table').html(data);
          }
      });
  }
  
  // Initial fetch
  fetchData();
  
  // Set interval to fetch data every 1 seconds
  setInterval(fetchData, 1000);
});
</script>