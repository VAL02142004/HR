<?php
include ('../conn/conn.php');

try {
    $fileID = $_GET['fileID'];
    $stmt = $conn->prepare("SELECT * FROM `tbl_file` WHERE tbl_file_id = :fileID");
    $stmt->bindParam(':fileID', $fileID);
    $stmt->execute();
    $fileData = $stmt->fetch();

    if ($fileData) {
        $file_path = $fileData['file'];
        $file_name = basename($file_path);

        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $file_name . '"');

        readfile($file_path);
    } else {
        echo 'File not found.';
    }
} catch (PDOException $e) {
    echo 'Database Error: ' . $e->getMessage();
}
?>
