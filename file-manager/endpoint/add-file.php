<?php
include ('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Check if the request is for a general file upload or an employee record with a contract file
        if (isset($_POST["fileTitle"]) && isset($_FILES["file"]["name"]) && isset($_POST["fileUploader"])) {
            // General File Upload
            $fileTitle = $_POST["fileTitle"];
            $fileUploader = $_POST["fileUploader"] ?? "";
            $uploadDirectory = "../file-uploads/";
            $uploadedFileName = basename($_FILES["file"]["name"]);
            $targetFilePath = $uploadDirectory . $uploadedFileName;

            // Check for duplicate filenames
            if (file_exists($targetFilePath)) {
                echo "<script>
                    alert('A file with the same name already exists. Please choose a different name.');
                    window.location.href = 'http://localhost/file-manager/index.php';
                </script>";
                exit();
            }

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                $dateUploaded = date("Y-m-d H:i:s");
                $stmt = $conn->prepare("INSERT INTO tbl_file (file_title, file, file_uploader, date_uploaded) VALUES (?, ?, ?, ?)");
                $stmt->execute([$fileTitle, $uploadedFileName, $fileUploader, $dateUploaded]);

                echo "<script>
                    alert('File uploaded and saved successfully!');
                    window.location.href = 'http://localhost/file-manager/index.php';
                </script>";
                exit();
            } else {
                throw new Exception('Error uploading the file.');
            }
        } elseif (isset($_POST['name']) && isset($_POST['salary_rate']) && isset($_POST['date_hired']) && isset($_POST['position'])) {
            // Employee Record with Contract File Upload
            $name = $_POST['name'];
            $salary_rate = $_POST['salary_rate'];
            $date_hired = $_POST['date_hired'];
            $position = $_POST['position'];

            // Check if 'contract_file' column exists
            $checkColumn = $conn->prepare("SHOW COLUMNS FROM users LIKE 'contract_file'");
            $checkColumn->execute();
            $columnExists = $checkColumn->rowCount() > 0;

            // Handle contract file upload
            $contract_file = "";
            if (!empty($_FILES["contract_file"]["name"])) {
                $target_dir = "../uploads/";
                $contract_file = basename($_FILES["contract_file"]["name"]);
                $target_file = $target_dir . $contract_file;

                if (!move_uploaded_file($_FILES["contract_file"]["tmp_name"], $target_file)) {
                    throw new Exception('Error uploading the contract file.');
                }
            }

            // Insert into users table
            $sql = "INSERT INTO users (name, salary_rate, date_hired, position";
            $params = [$name, $salary_rate, $date_hired, $position];

            if ($columnExists) {
                $sql .= ", contract_file";
                $params[] = $contract_file;
            }

            $sql .= ") VALUES (" . implode(',', array_fill(0, count($params), '?')) . ")";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            echo "<script>
                alert('Employee record added successfully!');
                window.location.href = '../index.php';
            </script>";
            exit();
        } else {
            throw new Exception('Please fill out the required fields.');
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Database Error: " . addslashes($e->getMessage()) . "');
            window.location.href = '../index.php';
        </script>";
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href = '../index.php';
        </script>";
    }
}
?>
