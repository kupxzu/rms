<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch departments and positions
$departments = $conn->query("SELECT * FROM departments");
$positions = $conn->query("SELECT * FROM positions");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments and Positions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Departments and Positions</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Manage Departments -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Departments</h3>
                                <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addDepartmentModal">Add Department with Position</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Positions</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $conn->query("SELECT d.id AS department_id, d.name AS department_name, GROUP_CONCAT(p.name SEPARATOR ', ') AS positions
                                                                FROM departments d
                                                                LEFT JOIN department_position dp ON d.id = dp.department_id
                                                                LEFT JOIN positions p ON dp.position_id = p.id
                                                                GROUP BY d.id, d.name");
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['department_id']; ?></td>
                                                <td><?php echo $row['department_name']; ?></td>
                                                <td><?php echo $row['positions'] ?: 'No Positions'; ?></td>
                                                <td>
                                                    <button class="btn btn-success btn-sm edit-department" data-id="<?php echo $row['department_id']; ?>" data-name="<?php echo $row['department_name']; ?>" data-toggle="modal" data-target="#editDepartmentModal">Edit</button>
                                                    <button class="btn btn-danger btn-sm delete-department" data-id="<?php echo $row['department_id']; ?>" data-toggle="modal" data-target="#deleteDepartmentModal">Delete</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modals -->
<!-- Add Department with Position Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="manage_departments_positions.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department with Position</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="department_name">Select Department</label>
                        <select name="department_id" class="form-control" required>
                            <option value="">Choose Department</option>
                            <?php
                            $departments = $conn->query("SELECT * FROM departments");
                            while ($dept = $departments->fetch_assoc()) {
                                echo "<option value='" . $dept['id'] . "'>" . $dept['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="positions">Assign Positions</label>
                        <select name="positions[]" class="form-control" multiple required>
                            <?php
                            $positions = $conn->query("SELECT * FROM positions");
                            while ($pos = $positions->fetch_assoc()) {
                                echo "<option value='" . $pos['id'] . "'>" . $pos['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_department_with_position" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="manage_departments_positions.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="department_id" id="edit_department_id">
                    <div class="form-group">
                        <label for="edit_department_name">Department Name</label>
                        <input type="text" name="department_name" id="edit_department_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_department" class="btn btn-success">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Department Modal -->
<div class="modal fade" id="deleteDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="manage_departments_positions.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Department</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this department?</p>
                    <input type="hidden" name="department_id" id="delete_department_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete_department" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).on('click', '.edit-department', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#edit_department_id').val(id);
        $('#edit_department_name').val(name);
    });

    $(document).on('click', '.delete-department', function () {
        const id = $(this).data('id');
        $('#delete_department_id').val(id);
    });
</script>
</body>
</html>
