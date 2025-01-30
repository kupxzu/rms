<?php
session_start();
require('../includes/fpdf/fpdf.php'); // Include FPDF Library

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    $allowed_extensions = ['jpg', 'jpeg', 'png'];
    if (!in_array($file_ext, $allowed_extensions)) {
        die("Invalid file type. Only JPG, JPEG, and PNG are allowed.");
    }

    // Load image and get dimensions
    list($width, $height) = getimagesize($file_tmp);
    
    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Auto-scale the image to fit within the PDF
    $pdf_width = 190; // Max width inside PDF (A4 size)
    $pdf_height = ($height / $width) * $pdf_width;
    
    $pdf->Image($file_tmp, 10, 10, $pdf_width, $pdf_height);

    // Generate unique file name
    $output_file = '../uploads/private/' . uniqid() . '.pdf';
    
    // Save PDF to server
    $pdf->Output($output_file, 'F');

    // Force download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="converted_image.pdf"');
    readfile($output_file);
    unlink($output_file); // Delete the file after download
    exit();
} else {
    echo "No image uploaded.";
}
