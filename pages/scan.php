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

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
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

$trainingTable = "training-$trainingID-$trainingDay";

$getParticipantStmt = $conn->prepare("SELECT * FROM `$trainingTable`");

if ($getParticipantStmt->execute()) {
	$getParticipantResult = $getParticipantStmt->get_result();
	if ($getParticipantResult->num_rows > 0) {

		$attendanceData = [];

		while ($getParticipantData = $getParticipantResult->fetch_assoc()) {
			$numID = $getParticipantData['participant_id'];
			$name = "{$getParticipantData['firstname']} {$getParticipantData['middle_initial']} {$getParticipantData['lastname']}";

			$attendanceData[] = [
				'numID' => $numID,
				'name' => $name,
				'timestamp' => "00:00:00"
			];

		}

	} else {
		echo "No participants";
	}
} else {
	echo $getParticipantStmt->error;
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

		#my-qr-reader video {
			width: 50% !important;
			/* Set the width to 50% of the parent container */
			/* height: 50% !important; */
			/* Set the height to 50% of the viewport height */
			max-width: 400px !important;
			/* Set the maximum width to 400px */
			max-height: 400px !important;
			/* Set the maximum height to 300px */
			margin: 0 auto !important;
			/* Center the video horizontally */
			aspect-ratio: 1/1 !important;
			object-fit: cover !important;
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

		td {
			border: 1px solid black;
			padding: 10px;
			cursor: pointer;
		}

		td:hover {
			background-color: #f0f0f0;
		}
	</style>
</head>

<body>
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
			<a class="btn" href="viewTraining.php?<?php echo "id=$trainingID"; ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
					class="bi bi-arrow-return-left" viewBox="0 0 16 16">
					<path fill-rule="evenodd"
						d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
				</svg>
			</a>
		</div>
	</header>


	<div class="container">
		<h2 class="text-center fw-bold"><?php
		require '../processes/db_connection.php';

		$stmt = $conn->prepare("SELECT * FROM trainings WHERE training_id = ?");
		$stmt->bind_param('s', $trainingID);
		$stmt->execute();
		$result = $stmt->get_result();
		$data = $result->fetch_assoc();

		$trainingName = $data['training_name'];
		echo $trainingName; ?>
		</h2>
		<div>
			<div class="section">
				<?php
				echo "
						<input type='hidden' value='$trainingID' name='training' id='training'>
						<input type='hidden' value='$trainingDay' name='days' id='days'>
						<input type='hidden' value='$trainingInORout' name='inORout' id='inORout'>
					";
				?>
				<p class="text-center">
					<?php
					$scanStatus = $trainingInORout == "in" ? "Login" : "Logout";
					echo "Day $trainingDay - $scanStatus";
					?>
				</p>
				<h2 id='status' class="text-center"></h2>
				<div id="my-qr-reader">
				</div><br>
				<table id="recorded" style="width: 100%; max-height: 2em;">
					<tr style="overflow: scroll;">

					</tr>
				</table>

				<div style="display: flex; gap: 1em; width: 100%; justify-content: center; align-items: center;">
					<button onclick="clearData()">Clear Data</button><button onclick="saveData()">Save to Server</button>
				</div>
				<!-- <div class="scanResult">
					<h3><label for="name-result" id="name-label"></label></h3>
					<input type="hidden" name="name-result" id="name-result"><br>
					<input type="submit" value="Save" id="saveButton" style="display: none;">
				</div> -->
			</div>
		</div>


	</div>
	<script src="../script/html5-qrcode.min.js"></script>
	<script src="../script/crypto-js.min.js"></script>
	<script src="../script/jquery-3.6.0.min.js"></script>

	<script>
		let qrCodeScanned = false;
		let previousData = "";
		setStatus("ready");

		const existingData = localStorage.getItem("attendance-<?php echo $trainingID; ?>-<?php echo $trainingInORout; ?>-<?php echo $trainingDay; ?>");
		var recordedData = [];

		if (existingData) {
			try {
				recordedData = JSON.parse(existingData);

				recordedData.forEach(participant => {
					addRow(participant.numID, participant.name, participant.timestamp);
				});
			} catch (error) {
				alert('Failed to parse existing data:', e);
				recordedData = [];
			}
		} else {
			console.log("None");
		}

		var attendanceData = JSON.parse('<?php echo json_encode($attendanceData); ?>');

		function domReady(fn) {
			if (
				document.readyState === "complete" ||
				document.readyState === "interactive"
			) {
				setTimeout(fn, 1000);
			} else {
				document.addEventListener("DOMContentLoaded", fn);
			}
		}

		let htmlscanner = new Html5QrcodeScanner(
			"my-qr-reader",
			{ fps: 50, qrbos: 250 }
		);

		function setStatus(status) {
			const statusElement = document.getElementById('status');

			switch (status) {
				case "ready":
					statusElement.innerHTML = 'Ready to Scan!';
					break;
				case "decrypting":
					statusElement.innerHTML = 'Decrypting data...';
					break;
				case "recording":
					statusElement.innerHTML = 'Recording data...';
					break;
				default:
					statusElement.innerHTML = 'Error. Refresh the page.';
					break;
			}
		}

		domReady(function () {
			// If found you qr code

			async function onScanSuccess(decodeText, decodeResult) {
				// alert("Scanned data: "+decodeText);
				if (!qrCodeScanned) { // Check if QR code has already been scanned
					qrCodeScanned = true;

					// alert(previousData + " -- "+ decodeText);

					if (previousData === decodeText) {
						qrCodeScanned = false;
						return;
					}
					else {
						previousData = decodeText;
						setStatus("decrypting");

						decodeText

						addAttendance(decodeText.split("::")[1]);

						setStatus("ready");
						qrCodeScanned = false;
					}

				}
			}

			// if (counter === 0){
			// 	counter++;
			// 	htmlscanner.render(onScanSuccess);
			// }

			// counter++;
			htmlscanner.render(onScanSuccess);
		});

		function addAttendance(numID) {
			console.log(numID);
			const participant = attendanceData.find(participant => participant.numID === parseInt(numID, 10));
			if (participant) {
				const now = new Date();

				// Get the current hour, minute, and second
				const hours = now.getHours();
				const minutes = now.getMinutes();
				const seconds = now.getSeconds();

				participant.timestamp = `${hours}:${minutes}:${seconds}`;

				saveRecordedData(participant.numID, participant.name, participant.timestamp);
			} else {
				console.log("Not found");
			}
		}

		function deleteLastData() {
			document.getElementById("recorded").innerHTML = "<tr></tr>";
			recordedData.pop();
			localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $trainingInORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
			recordedData.forEach(participant => {
				addRow(participant.numID, participant.name, participant.timestamp);
			});
		}

		function addRow(id, name, timestamp) {
			const tbody = document.querySelector('#recorded tbody');
			const newRow = document.createElement('tr');

			const idCell = document.createElement('td');
			idCell.textContent = id;
			newRow.appendChild(idCell);

			const nameCell = document.createElement('td');
			nameCell.textContent = name;
			newRow.appendChild(nameCell);

			const timestampCell = document.createElement('td');
			timestampCell.textContent = timestamp;
			newRow.appendChild(timestampCell);

			const firstRow = tbody.firstChild; // Get the first row
			tbody.insertBefore(newRow, firstRow);
		}

		function saveRecordedData(id, name, timestamp) {
			const newData = { numID: id, name: name, timestamp: timestamp };

			const dataExists = recordedData.find(participant => participant.numID === parseInt(id, 10));

			if (dataExists) {
				const updatedDataArray = recordedData.filter(item => item.numID !== parseInt(id, 10));
				recordedData = updatedDataArray;
			}

			document.getElementById("recorded").innerHTML = "<tr></tr>";
			recordedData.push(newData);
			recordedData.forEach(participant => {
				addRow(participant.numID, participant.name, participant.timestamp);
			});
			localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $trainingInORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));

		}

		function saveDatabase() {
			const data = JSON.stringify(recordedData);
			const blob = new Blob([data], { type: 'application/json' });
			const url = URL.createObjectURL(blob);
			const a = document.createElement('a');
			a.href = url;
			a.download = 'attendance.json';
			a.click();
			URL.revokeObjectURL(url);
		}

		function saveData() {
			const trainingID = "<?php echo $trainingID; ?>";
			const trainingDay = "<?php echo $trainingDay; ?>";
			const inorout = "<?php echo $trainingInORout; ?>";

			$.ajax({
				url: '../processes/saveRecordedData.php',
				type: 'POST',
				contentType: 'application/json',
				data: JSON.stringify({
					inorout: inorout,
					trainingID: trainingID,
					day: trainingDay,
					participants: recordedData
				}),
				success: function (response) {
					if (response) {
						document.getElementById("recorded").innerHTML = "<tr></tr>";
						recordedData = [];
						localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $trainingInORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
					}
				},
				error: function (xhr, status, error) {
					alert('Error:', error);
				}
			});
		}

		function clearData() {

			const confirmDelete = confirm("Are you sure you want to DELETE ALL DATA?");

			if (confirmDelete) {
				document.getElementById("recorded").innerHTML = "<tr></tr>";
				recordedData = [];
				localStorage.setItem("attendance-<?php echo $trainingID; ?>-<?php echo $trainingInORout; ?>-<?php echo $trainingDay; ?>", JSON.stringify(recordedData));
			}

		}
	</script>
</body>

</html>