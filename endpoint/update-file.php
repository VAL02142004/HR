<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileID = $_POST["fileID"];
    $fileTitle = $_POST["fileTitle"];
    $fileUploader = isset($_POST["fileUploader"]) ? $_POST["fileUploader"] : "";

    $newFileName = null;

    if ($_FILES["file"]["name"]) {
        $uploadDirectory = "../file-uploads/";
        $newFileName = $_FILES["file"]["name"];
        $targetFilePath = $uploadDirectory . $newFileName;

        // Check if the file already exists
        if (file_exists($targetFilePath)) {
            echo "
            <script>
                alert('A file with the same name already exists. Please choose a different name for your file.');
                window.location.href = 'http://localhost/file-manager-app/index.php';
            </script>";
        } else {
            // Delete the old file, if it exists
            $sqlGetOldFileName = "SELECT file FROM tbl_file WHERE tbl_file_id = :fileID";
            $stmtGetOldFileName = $conn->prepare($sqlGetOldFileName);
            $stmtGetOldFileName->bindParam(':fileID', $fileID);
            $stmtGetOldFileName->execute();
            $oldFileName = $stmtGetOldFileName->fetchColumn();

            if ($oldFileName) {
                $oldFilePath = $uploadDirectory . $oldFileName;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                // File uploaded successfully
            } else {
                echo "Error updating the file.";
                exit;
            }
        }
    }

    $sql = "UPDATE tbl_file SET file_title = :fileTitle, file_uploader = :fileUploader";
    if ($newFileName) {
        $sql .= ", file = :newFileName";
    }
    $sql .= " WHERE tbl_file_id = :fileID";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fileID', $fileID);
    $stmt->bindParam(':fileTitle', $fileTitle);
    $stmt->bindParam(':fileUploader', $fileUploader);
    if ($newFileName) {
        $stmt->bindParam(':newFileName', $newFileName);
    }

    if ($stmt->execute()) {
        echo "
        <script>
            alert('File updated successfully!');
            window.location.href = 'http://localhost/file-manager-app/index.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Error updating file information.');
            window.location.href = 'http://localhost/file-manager-app/index.php';
        </script>";
    }
}
?>
