<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: index.php');
        exit();
    }

		if($_SERVER["REQUEST_METHOD"] == 'POST'){
			$_SESSION['trainingID'] = $_POST['training'];
			$_SESSION['days'] = $_POST['days'];
			$_SESSION['inORout'] = $_POST['inORout'];

			$trainingID = $_SESSION['trainingID'];
			$trainingDay = $_SESSION['days'];
			$trainingInORout = $_SESSION['inORout'];
		} else {
			$trainingID = $_SESSION['trainingID'];
			$trainingDay = $_SESSION['days'];
			$trainingInORout = $_SESSION['inORout'];
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
		#html5-qrcode-button-camera-start {
			background-color: #EF4B4C;
			color: #E6FFFF;
		}

		#html5-qrcode-anchor-scan-type-change {
			color: #E6FFFF;
		}

		.container {
			width: 100%;
		}

		.container h1 {
			color: #2e2c2d;
			text-align: center;
		}

		.section {
			background-color: #ffffff;
			padding: 10px 30px;
			border: 1.5px solid #b2b2b2;
			border-radius: 0.25em;
			box-shadow: 0 20px 25px rgba(0, 0, 0, 0.25);
		}

		#my-qr-reader {
			padding: 20px !important;
			border: 1.5px solid #b2b2b2 !important;
			border-radius: 8px;
			background-color: #3D619B;
		}

		#my-qr-reader img[alt="Info icon"] {
			display: none;
		}

		#my-qr-reader img[alt="Camera based scan"] {
			width: 100px !important;
			height: 100px !important;
		}

		button {
			padding: 10px 20px;
			border: 1px solid #b2b2b2;
			outline: none;
			border-radius: 0.25em;
			color: white;
			font-size: 15px;
			cursor: pointer;
			margin-top: 15px;
			margin-bottom: 10px;
			background-color: #008000ad;
			transition: 0.3s background-color;
		}

		button:hover {
			background-color: #008000;
		}

		#html5-qrcode-anchor-scan-type-change {
			text-decoration: none !important;
			color: #1d9bf0;
		}

		video {
			width: 100% !important;
			border: 1px solid #b2b2b2 !important;
			border-radius: 0.25em;
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
      <a class="btn" href="viewTraining.php?<?php echo "id=$trainingID"; ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5"/>
				</svg>
      </a>
    </div>
  </header>
	

	<div class="container">
		<form action="../processes/attendanceProcess.php" method="post">
			<div class="section">
				<?php
					echo "
						<input type='hidden' value='$trainingID' name='training' id='training'>
						<input type='hidden' value='$trainingDay' name='days'>
						<input type='hidden' value='$trainingInORout' name='inORout'>
					";

					require '../processes/db_connection.php';

					$stmt = $conn->prepare("SELECT * FROM trainings WHERE training_id = ?");
					$stmt->bind_param('s', $trainingID);
					$stmt->execute();
					$result = $stmt->get_result();
					$data = $result->fetch_assoc();

					$trainingName = $data['training_name'];
				?>
				<h2 style="text-align: center;"><?php echo $trainingName; ?></h2>
				<div id="my-qr-reader">
				</div><br>
				<div class="scanResult">
					<h3><label for="name-result" id="name-label"></label></h3>
					<input type="hidden" name="name-result" id="name-result"><br>
					<input type="submit" value="Save" id="saveButton" style="display: none;">
				</div>
			</div>
		</form>
	</div>
	<script src="../script/html5-qrcode.min.js"></script>
	<script src="../script/main-script.js"></script>
	<script src="../script/crypto-js.min.js"></script>
</body>

</html>
