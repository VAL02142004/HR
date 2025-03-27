<?php
include('../conn/conn.php');

try {
    if (isset($_GET['fileID'])) {
        // Case 1: Downloading a general file from tbl_file
        $fileID = $_GET['fileID'];

        $stmt = $conn->prepare("SELECT file FROM tbl_file WHERE tbl_file_id = :fileID");
        $stmt->bindParam(':fileID', $fileID);
        $stmt->execute();
        $file_path = $stmt->fetchColumn();

        if ($file_path && file_exists("../file-uploads/" . $file_path)) {
            $fullPath = "../file-uploads/" . $file_path;
            $file_name = basename($fullPath);

            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"$file_name\"");
            readfile($fullPath);
            exit();
        } else {
            echo "File not found.";
        }
    } elseif (isset($_GET['id'])) {
        // Case 2: Downloading an employee contract file from users
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT contract_file FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetchColumn();

        if ($file && file_exists("../uploads/" . $file)) {
            $fullPath = "../uploads/" . $file;
            $file_name = basename($fullPath);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            readfile($fullPath);
            exit();
        } else {
            echo "File not found.";
        }
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo 'Database Error: ' . $e->getMessage();
}
?>
