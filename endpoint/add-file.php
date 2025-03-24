<?php
include('../conn/conn.php'); // Ensure this file correctly connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate if required fields exist
    if (!isset($_POST['name'], $_POST['salary_rate'], $_POST['date_hired'], $_POST['position'], $_FILES['file'])) {
        die("<script>alert('Missing form fields!'); window.location.href='../index.php';</script>");
    }

    $name = $_POST['name'];
    $salaryRate = $_POST['salary_rate'];
    $dateHired = $_POST['date_hired'];
    $position = $_POST['position'];
    $position = $_POST['position'];


    // Check if a file is uploaded
    if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        die("<script>alert('File upload error: " . $_FILES["file"]["error"] . "'); window.location.href='../index.php';</script>");
    }

    // File Upload Handling
    $targetDir = "../uploads/";
    $fileName = basename($_FILES["file"]["name"]);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    $targetFilePath = $targetDir . $fileName;

    // Allowed file formats
    $allowedTypes = array("pdf", "doc", "docx", "jpg", "png");

    if (!in_array(strtolower($fileType), $allowedTypes)) {
        die("<script>alert('Invalid file type! Allowed types: pdf, doc, docx, jpg, png'); window.location.href='../index.php';</script>");
    }

    // Ensure uploads directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Move uploaded file to target directory
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        die("<script>alert('Error moving uploaded file!'); window.location.href='../index.php';</script>");
    }

    // Insert into Database
    try {
        $stmt = $conn->prepare("INSERT INTO tbl_file (name, salary_rate, date_hired, position, contract_file) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $salaryRate, $dateHired, $position, $fileName])) {
            echo "<script>alert('File uploaded successfully!'); window.location.href='../index.php';</script>";
        } else {
            die("<script>alert('Database insertion error!'); window.location.href='../index.php';</script>");
        }
    } catch (PDOException $e) {
        die("<script>alert('Database error: " . $e->getMessage() . "'); window.location.href='../index.php';</script>");
    }
} else {
    die("<script>alert('Invalid request!'); window.location.href='../index.php';</script>");
}
?>
