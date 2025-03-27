<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_POST["id"])) {
            // Case 1: Updating employee record in `users` table
            $id = $_POST['id'];
            $name = $_POST['name'];
            $salary_rate = $_POST['salary_rate'];
            $date_hired = $_POST['date_hired'];
            $position = $_POST['position'];

            // Handle contract file upload
            $contract_file = $_POST['old_contract_file'];
            if (!empty($_FILES["contract_file"]["name"])) {
                $target_dir = "../uploads/";
                $contract_file = basename($_FILES["contract_file"]["name"]);
                $target_file = $target_dir . $contract_file;
                
                // Move new file
                move_uploaded_file($_FILES["contract_file"]["tmp_name"], $target_file);
            }

            // Update employee record
            $stmt = $conn->prepare("UPDATE users SET name=?, salary_rate=?, date_hired=?, position=?, contract_file=? WHERE id=?");
            $stmt->execute([$name, $salary_rate, $date_hired, $position, $contract_file, $id]);

            echo "<script>
                alert('Employee record updated successfully!');
                window.location.href = '../index.php';
            </script>";
            exit();
        }

        if (isset($_POST["fileID"])) {
            // Case 2: Updating file record in `tbl_file`
            $fileID = $_POST["fileID"];
            $fileTitle = $_POST["fileTitle"];
            $fileUploader = isset($_POST["fileUploader"]) ? $_POST["fileUploader"] : "";

            $newFileName = null;
            $uploadDirectory = "../file-uploads/";

            if (!empty($_FILES["file"]["name"])) {
                $newFileName = $_FILES["file"]["name"];
                $targetFilePath = $uploadDirectory . $newFileName;

                // Check if the file already exists
                if (file_exists($targetFilePath)) {
                    echo "<script>
                        alert('A file with the same name already exists. Please choose a different name.');
                        window.location.href = 'http://localhost/file-manager/index.php';
                    </script>";
                    exit();
                }

                // Get old file name
                $stmtOldFile = $conn->prepare("SELECT file FROM tbl_file WHERE tbl_file_id = :fileID");
                $stmtOldFile->bindParam(':fileID', $fileID);
                $stmtOldFile->execute();
                $oldFileName = $stmtOldFile->fetchColumn();

                if ($oldFileName) {
                    $oldFilePath = $uploadDirectory . $oldFileName;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Move new file
                if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                    echo "<script>
                        alert('Error updating the file.');
                        window.location.href = 'http://localhost/file-manager/index.php';
                    </script>";
                    exit();
                }
            }

            // Update database record
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

            $stmt->execute();

            echo "<script>
                alert('File updated successfully!');
                window.location.href = 'http://localhost/file-manager/index.php';
            </script>";
            exit();
        }

    } catch (PDOException $e) {
        echo "<script>
            alert('Database Error: " . $e->getMessage() . "');
            window.location.href = '../index.php';
        </script>";
    }
}
?>
