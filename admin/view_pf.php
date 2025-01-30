<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>


<body class="hold-transition sidebar-mini layout-fixed">

<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>



<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Uploaded Files with User Details</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List of Uploaded Files</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>File Name</th>
                                <th>Type</th>
                                <th>Uploaded By</th>
                                <th>Department and Position</th>
                                <th>Uploaded At</th>
                                <th hidden>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT 
                                        private_files.id AS file_id,
                                        private_files.file_name,
                                        private_files.file_type,
                                        private_files.uploaded_at,
                                        users.firstname,
                                        users.lastname,
                                        departments.name AS department_name,
                                        positions.name AS position_name
                                      FROM private_files
                                      JOIN users ON private_files.uploaded_by = users.id
                                      JOIN department_position ON users.id_dp = department_position.id
                                      JOIN departments ON department_position.department_id = departments.id
                                      JOIN positions ON department_position.position_id = positions.id
                                      ORDER BY private_files.uploaded_at DESC";
                            
                            $result = mysqli_query($conn, $query);
                            $count = 1;

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $count++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['file_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['file_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['firstname'] . " " . $row['lastname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['department_name'] . " | " . $row['position_name'] ) . "</td>";
                                echo "<td>" . htmlspecialchars($row['uploaded_at']) . "</td>";
                                echo "<td hidden>
                                        <a href='download_private_file.php?file_id=" . $row['file_id'] . "' class='btn btn-sm btn-primary'>
                                            <i class='fas fa-download'></i> Download
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
