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
    <?include '../includes/notifications.php'; ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- Manage Departments -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Departments</h3>
                                <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addDepartmentModal"><i class="fas fa-plus"></i> Department</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $conn->query("SELECT * FROM departments");
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td>
                                                    <button class="btn btn-success btn-sm edit-department" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" data-toggle="modal" data-target="#editDepartmentModal"><i class="fas fa-edit"></i>Edit</button>
                                                    <button class="btn btn-danger btn-sm delete-department" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#deleteDepartmentModal"><i class="fas fa-trash"></i> Delete</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Manage Positions -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Positions</h3>
                                <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addPositionModal"><i class="fas fa-plus"></i> Position</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $conn->query("SELECT * FROM positions");
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td>
                                                    <button class="btn btn-success btn-sm edit-position" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" data-toggle="modal" data-target="#editPositionModal"><i class="fas fa-edit"></i> Edit</button>
                                                    <button class="btn btn-danger btn-sm delete-position" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#deletePositionModal"><i class="fas fa-trash"></i> Delete</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Settings</h3>

                            </div>
                            <div class="card-body">
                            <a href="manage_dp.php" class="btn btn-primary btn-sm float-left"><i class="fas fa-code-fork"></i> Set Department Position</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modals -->
<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="ade-dp.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="department_name">Department Name</label>
                        <input type="text" name="department_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_department" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="ade-dp.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Department</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="department_id" id="edit_department_id">
                    <div class="form-group">
                        <label for="edit_department_name">Edit Department</label>
                        <input type="text" name="department_name" id="edit_department_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_department" class="btn btn-primary"><i class="fas fa-check" ></i> update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Department Modal -->
<div class="modal fade" id="deleteDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="ade-dp.php" method="POST">
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

<!-- Add Position Modal -->
<div class="modal fade" id="addPositionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="ade-dp.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Position</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="position_name">Position Name</label>
                        <input type="text" name="position_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_position" class="btn btn-primary"> Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Position Modal -->
<div class="modal fade" id="editPositionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="ade-dp.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Position</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="position_id" id="edit_position_id">
                    <div class="form-group">
                        <label for="edit_position_name">Position Name</label>
                        <input type="text" name="position_name" id="edit_position_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_position"  class="btn btn-primary"><i class="fas fa-check" ></i> update</
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Position Modal -->
<div class="modal fade" id="deletePositionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="ade-dp.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Position</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this position?</p>
                    <input type="hidden" name="position_id" id="delete_position_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete_position" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
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

    $(document).on('click', '.edit-position', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#edit_position_id').val(id);
        $('#edit_position_name').val(name);
    });

    $(document).on('click', '.delete-position', function () {
        const id = $(this).data('id');
        $('#delete_position_id').val(id);
    });
</script>


<script>
    $(document).ready(function () {
        setTimeout(function () {
            $(".alert").alert('close');
        }, 4000); // 4 seconds
    });
</script>

</body>
</html>