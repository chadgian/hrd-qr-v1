<?php

include 'db_connection.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$trainingID = $data['trainingID'];
$trainingDay = $data['day'];
$participants = $data['participants'];
$inorout = "log{$data['inorout']}";

echo $inorout;

$trainingTable = "training-$trainingID-$trainingDay";
$status = false;
foreach ($participants as $participant) {
  $participantID = $participant['numID'];
  $name = $participant['name'];
  $timestamp = $participant['timestamp'];

  $saveDataStmt = $conn->prepare("UPDATE `$trainingTable` SET $inorout = ? WHERE participant_id = ?");
  $saveDataStmt->bind_param("si", $timestamp, $participantID);

  if ($saveDataStmt->execute()) {
    $status = true;
  } else {
    $status = false;
  }
}

echo $status;