<?php
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Allow POST and OPTIONS requests
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow Content-Type and Authorization headers

// Other headers you may want to set
header("Content-Type: application/json");

// Check if the form is submitted and the file is uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    // File upload directory
    $uploadDir = './uploads/';
    // File name with extension
    $fileName = basename($_FILES['file']['name']);
    // File path on the server
    $filePath = $uploadDir . $fileName;

    // Check if the file already exists
    if (file_exists($filePath)) {
        echo json_encode(array("success" => false, "message" => "File already exists"));
        exit();
    }

    // Check if the file is valid and move it to the upload directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        echo json_encode(array("success" => true, "message" => "File uploaded successfully", "fileName" => $fileName, "filePath" => $filePath));
        exit();
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to upload file"));
        exit();
    }
} else {
    echo json_encode(array("success" => false, "message" => "No file uploaded"));
    exit();
}
