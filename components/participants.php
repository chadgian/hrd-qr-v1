<?php
  include '../processes/db_connection.php';
?>
<div class="container d-flex flex-column justify-content-center align-items-center text-center">
  <h4><?php echo $trainingName; ?></h4>
  <hr style="width: 100%;" class="my-1">
  <h1>PARTICIPANTS</h1>
  <input id="search-participants" class="form-control mb-3 mt-1 text-dark" type="search" placeholder="Search name" aria-label="Search" style="border-radius: 2rem; background-color: #D9D9D9">
</div>

<section id="participants-table" class="container-lg" style="overflow: scroll; height: 100%;">
  <table class='table table-secondary table-striped' style="width: 100%;">
    <thead>
      <tr>
        <th class="text-center" style="position: sticky; top: 0;">No.</th>
        <th class="text-center" style="position: sticky; top: 0;">Name</th>
        <th class="text-center" style="position: sticky; top: 0;">Agency</th>
        <th class="text-center" style="position: sticky; top: 0;">ID</th>
      </tr>
    </thead>
    <tbody id="table-body">
      <!-- Participants data will be populated here -->
    </tbody>
  </table>
</section>

<script>
  $(document).ready(function(){
    const searchInput = document.getElementById("search-participants");
    var trainingID = <?php echo json_encode($trainingID); ?>;
    fetchData(""); // Fetch all participants for the first time
    searchInput.addEventListener("input", function(event){
      var searchValue = event.target.value;
      fetchData(searchValue);
    });

    function fetchData(searchValue) {
        $.ajax({
            url: '../processes/fetchParticipants.php',
            type: 'GET',
            data:{
                search: searchValue,
                id: trainingID
            },
            success: function(data) {
                $('#table-body').html(data);
            }
        });
    }
  });

  var element = document.getElementById("participants-table");

  // Calculate the height from the element's position to the bottom of the screen
  var windowHeight = window.innerHeight;
  var elementTop = element.getBoundingClientRect().top;
  var heightFromTop = windowHeight - elementTop;

  // Set the calculated height to the element
  element.style.height = heightFromTop + "px";
</script>