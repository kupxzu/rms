<?php
session_start();
require('../../../includes/fpdf.php'); // Include FPDF Library
include '../../../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    die("Unauthorized access.");
}

$file_id = $_GET['file_id'] ?? null;
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

if (!$file_id) {
    die("Invalid request.");
}

// Check if the user is a Mayor or Vice Mayor
$is_mayor_or_vicemayor = in_array($user_role, ['Mayor', 'ViceMayor']);

// Fetch file details
if ($is_mayor_or_vicemayor) {
    // Mayors & Vice Mayors can download all files
    $sql = "SELECT file_name, file_type FROM private_files WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id);
} else {
    // Normal users can only download their own files
    $sql = "SELECT file_name, file_type FROM private_files WHERE id = ? AND uploaded_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $file_id, $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$file = $result->fetch_assoc();

if (!$file) {
    die("File not found or access denied.");
}

// Get file path
$file_path = "../../../uploads/private/" . $file['file_name'];

if (!file_exists($file_path)) {
    die("File not found.");
}

$file_ext = strtolower($file['file_type']);

// Check if the file is an image (JPG, PNG) and needs conversion
$allowed_image_types = ['jpg', 'jpeg', 'png'];
if (in_array($file_ext, $allowed_image_types)) {
    // Convert Image to PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Get image dimensions
    list($width, $height) = getimagesize($file_path);
    
    // Auto-scale the image to fit within the PDF
    $pdf_width = 190; // Max width inside PDF (A4 size)
    $pdf_height = ($height / $width) * $pdf_width;

    $pdf->Image($file_path, 10, 10, $pdf_width, $pdf_height);

    // Set output file name
    $output_pdf = "../../../uploads/private/" . pathinfo($file_path, PATHINFO_FILENAME) . ".pdf";

    // Save PDF file temporarily
    $pdf->Output($output_pdf, 'F');

    // Serve the converted PDF
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=\"" . basename($output_pdf) . "\"");
    readfile($output_pdf);
    
    // Delete the temporary PDF file
    unlink($output_pdf);
    exit();
}

// Serve other file types normally
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . basename($file_path) . "\"");
header("Content-Length: " . filesize($file_path));
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");

readfile($file_path);
exit();
