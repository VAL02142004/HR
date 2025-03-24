<?php
include ('./conn/conn.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager App</title>

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

    <!-- Add File Modal -->
    <div class="modal fade" id="addFileModal" tabindex="-1" aria-labelledby="addFile" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFile">Add File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>   
                </div>
                <div class="modal-body">
                    <form action="./endpoint/add-file.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Salary Rate</label>
                            <input type="text" class="form-control" name="salary_rate" required>
                        </div>
                        <div class="form-group">
                            <label>Date Hired</label>
                            <input type="date" class="form-control" name="date_hired" required>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" class="form-control" name="position" required>
                        </div>
                        <div class="form-group">
                            <label>Contract File</label>
                            <input type="file" class="form-control-file" name="contract_file" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Add File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="alert alert-dark text-center" role="alert">
            <h2>File Manager App</h2>
        </div>
        <div class="file-container">
            <!-- Add File Button -->
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
                        // Fetch data from the database
                        $stmt = $conn->prepare("SELECT * FROM tbl_file");
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (empty($result)) {
                            echo "<tr><td colspan='7'>No records found!</td></tr>";
                        } else {
                            foreach ($result as $row) {
                                $fileID = $row['tbl_file_id'];
                                $name = $row['name'] ?? 'N/A';
                                $salaryRate = $row['salary_rate'] ?? 'N/A';
                                $dateHired = $row['date_hired'] ?? 'N/A';
                                $position = $row['position'] ?? 'N/A';
                                $file = $row['contract_file'] ?? 'N/A';
                    ?>
                    <tr>
                        <td><?php echo $fileID; ?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $salaryRate; ?></td>
                        <td><?php echo $dateHired; ?></td>
                        <td><?php echo $position; ?></td>
                        <td>
                            <?php if ($file !== 'N/A') { ?>
                                <a href="./uploads/<?php echo $file; ?>" target="_blank"><?php echo $file; ?></a>
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
                                    <a href="./uploads/<?php echo $file; ?>" download class="btn btn-success btn-sm"><i class="fa-solid fa-download" title="Download"></i></a>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="updateFile(<?php echo $fileID; ?>)"><i class="fa-solid fa-pencil" title="Update"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteFile(<?php echo $fileID; ?>)"><i class="fa-solid fa-trash" title="Delete"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php 
                            } // End of foreach loop
                        } // End of if-else
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

    <!-- Script JS -->
    <script src="./assets/script.js"></script>

    <!-- Data Tables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

</body>
</html>
