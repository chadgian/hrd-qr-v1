<?php
    session_start();
    include_once '../processes/db_connection.php';

    if (isset($_SESSION['username'])) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result=$stmt->get_result();

        if($result->num_rows <= 0){
            header('Location: index.php');
            exit();
        }
    } else {
        header('Location: index.php');
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
  <script src="../script/js-bootstrap/bootstrap.min.js"></script>
  <script src="../script/jquery-3.6.0.min.js"></script>

  <style>
    .accordion-body {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      align-items: center;
    }

    .body-content {
      background-color: #EFEFEF;
      height: auto;
      border-radius: 1rem;
      overflow: scroll;
      padding: 1rem;
      width: 100%;
      text-decoration: none;
    }
  </style>
<body>
<?php
  // Path to the directory
  $directory = '../generated_ids/';

  // Check if the directory exists
  if (is_dir($directory)) {
      // Delete files and directories older than one day
      deleteOldFiles($directory);
  } else {
      echo "Directory does not exist.";
  }

  // Function to recursively delete files and directories older than one day
  function deleteOldFiles($directory) {
      // Get the list of files and directories in the directory
      $items = scandir($directory);
      
      // Iterate through the list
      foreach ($items as $item) {
          // Skip the current directory (.) and parent directory (..)
          if ($item === '.' || $item === '..') {
              continue;
          }
          
          // Construct the full path to the item
          $path = $directory . '/' . $item;
          
          // If the item is a directory, recursively delete its contents
          if (is_dir($path)) {
              // Recursively delete files and subdirectories
              deleteOldFiles($path);
              
              // Check if the directory is empty after deleting its contents
              if (is_dirEmpty($path)) {
                  // If the directory is empty, delete it
                  rmdir($path);
              }
          } else {
              // If the item is a file, delete it if it's older than one day
              if (filemtime($path) < strtotime('-1 day')) {
                  unlink($path);
              }
          }
      }
  }

  // Function to check if a directory is empty
  function is_dirEmpty($dir) {
      return count(glob($dir . '/*')) === 0;
  }
?>



  <?php include "../components/header.html"; ?>
  <?php include "../components/body.html"; ?>


</body>
</html>