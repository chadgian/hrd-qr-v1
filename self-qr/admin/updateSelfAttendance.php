<?php

include "../../processes/db_connection.php";

$day1date = (new DateTime($_POST['day1date']))->format("d-m-Y");
$day2date = (new DateTime($_POST['day2date']))->format("d-m-Y");

$trainingID = $_POST['training'];

$day1loginStart = $_POST['day1loginStart'];
$day1loginEnd = $_POST['day1loginEnd'];
$day1logoutStart = $_POST['day1logoutStart'];
$day1logoutEnd = $_POST['day1logoutEnd'];

$day2loginStart = $_POST['day2loginStart'];
$day2loginEnd = $_POST['day2loginEnd'];
$day2logoutStart = $_POST['day2logoutStart'];
$day2logoutEnd = $_POST['day2logoutEnd'];

$newData = "{
  \"trainingID\" : \"$trainingID\",
  \"day1date\" : \"$day1date\",
  \"day1loginStartTime\" : \"$day1loginStart\",
  \"day1loginEndTime\" : \"$day1loginEnd\",
  \"day1logoutStartTime\" : \"$day1logoutStart\",
  \"day1logoutEndTime\" : \"$day1logoutEnd\",
  \"day2date\" : \"$day2date\",
  \"day2loginStartTime\" : \"$day2loginStart\",
  \"day2loginEndTime\" : \"$day2loginEnd\",
  \"day2logoutStartTime\" : \"$day2logoutStart\",
  \"day2logoutEndTime\" : \"$day2logoutEnd\"
}";

$newDataArray = json_decode($newData, true);

$error = 0;

foreach ($newDataArray as $detailName => $detailData) {
  echo "$detailName => $detailData <br>";

  $updateData = $conn->prepare("UPDATE `_self-attendance-details` SET detailData = ? WHERE detailName = ?");
  $updateData->bind_param("ss", $detailData, $detailName);

  if ($updateData->execute()) {
    continue;
  } else {
    $error = 1;
    break;
  }
}

if ($error == 0) {
  header("Location: /hrd-qr-v1/self-qr/admin/");
  exit();
}