<?php
session_start();
require('../includes/fpdf.php'); 
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (!isset($_GET['file_id']) || empty($_GET['file_id'])) {
    die("Invalid request.");
}

$file_id = $_GET['file_id'];
$user_id = $_SESSION['user_id'];


$sql = "SELECT file_name, file_type FROM private_files WHERE id = ? AND uploaded_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $file_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$file = $result->fetch_assoc();

if (!$file) {
    die("File not found or access denied.");
}


$file_path = "../uploads/private/" . $file['file_name'];

if (!file_exists($file_path)) {
    die("File not found.");
}

$file_ext = strtolower($file['file_type']);

$allowed_image_types = ['jpg', 'jpeg', 'png'];
if (in_array($file_ext, $allowed_image_types)) {
    $pdf = new FPDF();
    $pdf->AddPage();


    list($width, $height) = getimagesize($file_path);
  
    $pdf_width = 190;
    $pdf_height = ($height / $width) * $pdf_width;

    $pdf->Image($file_path, 10, 10, $pdf_width, $pdf_height);

    $output_pdf = "../uploads/private/" . pathinfo($file_path, PATHINFO_FILENAME) . ".pdf";

    $pdf->Output($output_pdf, 'F');

    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=\"" . basename($output_pdf) . "\"");
    readfile($output_pdf);
    
    unlink($output_pdf);
    exit();
}

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . basename($file_path) . "\"");
header("Content-Length: " . filesize($file_path));
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");

readfile($file_path);
exit();
