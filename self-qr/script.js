// Create the Html5QrcodeScanner instance
const scanner = new Html5QrcodeScanner("reader", {
  fps: 10,
  qrbox: 250,
  supportedScanTypes: [ Html5QrcodeScanType.SCAN_TYPE_CAMERA ],
  rememberLastUsedCamera: true,
  // aspectRatio: 1.7777778
});

// Get the results element and again button
const resultsElement = document.getElementById("results");
const againButton = document.getElementById("again-btn");

// Define the onScanSuccess function
function onScanSuccess(decodedText, decodedResult) {
  // Create a new paragraph element to display the result
  // const newResult = document.createElement("p");
  // newResult.innerHTML = decodedText;

  // // Add the new result to the results element
  // resultsElement.appendChild(newResult);

  // resultsElement.innerHTML(decodedText);
  // alert(decodedText);
  var paxName;
  if (decodedText.split("::")[0] == 24){
    participants.forEach(participant => {
      if (participant['id'] == decodedText.split("::")[1]){
        paxName = participant['name'];
      }
    });
  }

  $("#statusModal").modal("toggle");
  const username = $("#username").val();

    setTimeout(() => {
      if (decodedText.split("::")[0] == trainingID){
        $.ajax({
          type: "POST",
          url: "processAttendance.php",
          data: {
            "username": username,
            "paxID": decodedText.split("::")[1],
            "trainingID": decodedText.split("::")[0]
            },
            success: function(data) {
              if (data == "ok"){
                $("#statusModal").modal("toggle");
                $("#resultModalLabel").html("Attendance Success!")
                $("#paxName").html(paxName);

                $("#resultModal").modal("toggle");
              } else {
                $("#statusModal").modal("toggle");
                alert(data);
              }
            }
        }); 
      } else {
        $("#statusModal").modal("toggle");
        alert("Wrong Training!");
      }
    }, 1000);

  // Clear the scanner and show the again button
  scanner.pause();
  againButton.style.display = "block";
}

// Define the scanAgain function
function scanAgain() {
  // Scan again and hide the again button
  scanner.resume();
  againButton.style.display = "none";
}

// Render the scanner with the onScanSuccess function
scanner.render(onScanSuccess);