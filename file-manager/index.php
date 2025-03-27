<?php
include ('./conn/conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="HR Management System">
    <title>CONTRACTS</title> 

    <!-- Style CSS -->
    <link rel="stylesheet" href="./assets/style.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

    <!-- Data Table -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>
<body>
    <div class="main">
        <div class="alert alert-dark text-center" role="alert">
            <h2>CONTRACTS</h2>
        </div>
        <div class="file-container">
            <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#addFileModal" style="width: 120px">
                <i class="fa-solid fa-plus"></i> Add File
            </button>

            <table class="table table-hover text-center" id="fileTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Salary Rate</th>
                        <th>Date Hired</th>
                        <th>Position</th>
                        <th>Contract File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                require './conn/conn.php';

                try {
                    $stmt = $conn->prepare("SELECT * FROM users"); 
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($result)) {
                        echo "<tr><td colspan='7'>No records found!</td></tr>";
                    } else {
                        foreach ($result as $row) {
                            $userID = $row['id'];
                            $name = $row['name'] ?? '';
                            $salaryRate = $row['salary_rate'] ?? '';
                            $dateHired = $row['date_hired'] ?? '';
                            $position = $row['position'] ?? '';
                            $file = $row['contract_file'] ?? 'N/A';
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($userID); ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($salaryRate); ?></td>
                    <td><?php echo htmlspecialchars($dateHired); ?></td>
                    <td><?php echo htmlspecialchars($position); ?></td>
                    <td>
                        <?php if ($file !== 'N/A') { ?>
                            <a href="./uploads/<?php echo htmlspecialchars($file); ?>" target="_blank"><?php echo htmlspecialchars($file); ?></a>
                        <?php } else {
                            echo "No file";
                        } ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu text-center">
                                <a href="./uploads/<?php echo htmlspecialchars($file); ?>" download class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-download" title="Download"></i>
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="updateFile(<?php echo htmlspecialchars($userID); ?>)">
                                    <i class="fa-solid fa-pencil" title="Update"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile(<?php echo htmlspecialchars($userID); ?>)">
                                    <i class="fa-solid fa-trash" title="Delete"></i>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php 
                        }
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Data Tables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
    $(document).ready(function() {
        $('#fileTable').DataTable();
    });
    </script>
</body>
</html>
