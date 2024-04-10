<?php
require_once 'db_connection.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


// Function to format time values
function formatTime($time) {
    return date('H:i:s', strtotime($time));
}

$trainingID = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM trainings WHERE training_id=?");
$stmt->bind_param('s', $trainingID); 
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$trainingName = $data['training_name'];
$trainingDays = $data['training_days'];

// Create an empty array to store the contents of each CSV file
$csvFiles = array();

// Loop through each day of training
for ($i = 1; $i <= $trainingDays; $i++) {
    $trainingTable = "training-" . $trainingID . "-" . strval($i);
    $sql = "SELECT * FROM `$trainingTable` ORDER BY participant_id ASC";
    $sql = $conn->prepare($sql);
    $sql->execute();
    $result = $sql->get_result();

    $stmt = $conn->prepare("SELECT * FROM `$trainingTable`");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result -> num_rows > 0){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Last Name');
        $sheet->setCellValue('C1', 'First Name');
        $sheet->setCellValue('D1', 'Middle Name');
        $sheet->setCellValue('E1', 'Agency');
        $sheet->setCellValue('F1', 'Login');
        $sheet->setCellValue('G1', 'Logout');

        $rowCount = 2;

        while($data = $result->fetch_assoc()){
            $loginTime = ($data['login'] == !NULL) ? date('H:i', strtotime($data['login'])) : "";
            $logoutTime = ($data['logout'] == !NULL) ? date('H:i', strtotime($data['logout'])) : "";
            $sheet->setCellValue('A'.$rowCount, $data['participant_id']);
            $sheet->setCellValue('B'.$rowCount, $data['lastname']);
            $sheet->setCellValue('C'.$rowCount, $data['firstname']);
            $sheet->setCellValue('D'.$rowCount, $data['middle_initial']);
            $sheet->setCellValue('E'.$rowCount, $data['agency']);
            $sheet->setCellValue('F'.$rowCount, $loginTime);
            $sheet->setCellValue('G'.$rowCount, $logoutTime);
            $rowCount++;
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelData = ob_get_clean();

        $excelFiles["{$trainingName}-Day-{$i}.xlsx"] = $excelData;
    } else {
        echo "No data found in the table";
        exit();
    }
}

// Create a ZIP archive
$zipFileName = 'training-files.zip';
$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
    // Add each CSV file to the ZIP archive
    foreach ($excelFiles as $filename => $content) {
        $zip->addFromString($filename, $content);
    }
    $zip->close();

    // Send the ZIP archive to the browser for download
    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename=$zipFileName");
    readfile($zipFileName);

    // Delete the ZIP file after sending it
    unlink($zipFileName);
} else {
    echo "Failed to create ZIP file.";
}

// Close connection
$conn->close();
?>
