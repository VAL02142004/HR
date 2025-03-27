<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["file"])) {
        // Case 1: Deleting a general file from tbl_file
        $fileID = $_GET["file"];

        // Get the file name from the database
        $sqlGetFileName = "SELECT file FROM tbl_file WHERE tbl_file_id = :fileID";
        $stmtGetFileName = $conn->prepare($sqlGetFileName);
        $stmtGetFileName->bindParam(':fileID', $fileID);
        $stmtGetFileName->execute();
        $fileName = $stmtGetFileName->fetchColumn();

        $uploadDirectory = "../file-uploads/";
        $filePath = $uploadDirectory . $fileName;

        if ($fileName && file_exists($filePath) && unlink($filePath)) {
            // Delete the file record from the database
            $stmtDeleteFile = $conn->prepare("DELETE FROM tbl_file WHERE tbl_file_id = :fileID");
            $stmtDeleteFile->bindParam(':fileID', $fileID);

            if ($stmtDeleteFile->execute()) {
                echo "<script>
                    alert('File deleted successfully!');
                    window.location.href = 'http://localhost/file-manager/index.php';
                </script>";
                exit();
            } else {
                echo "<script>
                    alert('Error deleting file record from the database.');
                    window.location.href = 'http://localhost/file-manager/index.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Error deleting the file.');
                window.location.href = 'http://localhost/file-manager/index.php';
            </script>";
        }
    } elseif (isset($_GET['id'])) {
        // Case 2: Deleting an employee contract file from users
        $id = $_GET['id'];

        // Get the file name
        $stmt = $conn->prepare("SELECT contract_file FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetchColumn();

        $contractDirectory = "../uploads/";
        $contractFilePath = $contractDirectory . $file;

        if ($file && file_exists($contractFilePath) && unlink($contractFilePath)) {
            // Delete record from database
            $stmtDeleteUser = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmtDeleteUser->execute([$id]);

            echo "<script>
                alert('Employee record and contract file deleted successfully!');
                window.location.href = 'http://localhost/file-manager/index.php';
            </script>";
            exit();
        } else {
            echo "<script>
                alert('Error deleting the contract file.');
                window.location.href = 'http://localhost/file-manager/index.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Invalid request.');
            window.location.href = 'http://localhost/file-manager/index.php';
        </script>";
    }
}
?>
