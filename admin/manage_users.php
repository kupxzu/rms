<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch users with their linked department and position
$sql = "SELECT 
            users.id, 
            users.username, 
            dp.id AS id_dp, 
            d.name AS department_name, 
            p.name AS position_name 
        FROM users 
        LEFT JOIN department_position dp ON users.id_dp = dp.id 
        LEFT JOIN departments d ON dp.department_id = d.id 
        LEFT JOIN positions p ON dp.position_id = p.id
        WHERE users.active = 1"; // Only fetch active users

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>Manage Users</h1>
        </section>
        <?php include '../includes/notifications.php'; ?>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-user-plus"></i> Add User
                    </button>

                    <a href="user_change_password.php" class="btn btn-warning btn-sm"><i class="fas fa-key"></i> Password</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['department_name'] ?: 'Unassigned'; ?></td>
                                    <td><?php echo $row['position_name'] ?: 'Unassigned'; ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm edit-user" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-id-dp="<?php echo $row['id_dp']; ?>" 
                                                data-username="<?php echo $row['username']; ?>" 
                                                data-toggle="modal" 
                                                data-target="#editUserModal"><i class="fas fa-edit"></i> Edit</button>
                                                <button class="btn btn-sm btn-danger archive-user" data-id="<?= $row['id'] ?>">
                        <i class="fas fa-archive"></i> Archive
                    </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>


<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="function/add_user.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" name="firstname" id="firstname" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" name="age" id="age" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sex">Sex</label>
                                <select name="sex" id="sex" class="form-control" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact">Contact</label>
                                <input type="text" name="contact" id="contact" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <?php
                            $departments = $conn->query("SELECT * FROM departments");
                            while ($department = $departments->fetch_assoc()) {
                                echo "<option value='{$department['id']}'>{$department['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <select name="id_dp" id="position" class="form-control" required>
                            <option value="">Select Position</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="function/edit_user.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">

                    <div class="row">
                        <!-- First Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_firstname">First Name</label>
                                <input type="text" name="firstname" id="edit_firstname" class="form-control" required>
                            </div>
                        </div>
                        <!-- Last Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_lastname">Last Name</label>
                                <input type="text" name="lastname" id="edit_lastname" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Age -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_age">Age</label>
                                <input type="number" name="age" id="edit_age" class="form-control" min="1" required>
                            </div>
                        </div>
                        <!-- Sex -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_sex">Sex</label>
                                <select name="sex" id="edit_sex" class="form-control" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <!-- Contact -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_contact">Contact</label>
                                <input type="text" name="contact" id="edit_contact" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>

                    <!-- Department -->
                    <div class="form-group">
                        <label for="edit_department">Department</label>
                        <select name="department" id="edit_department" class="form-control" required>
                            <option value="">Select Department</option>
                            <?php
                            $departments = $conn->query("SELECT * FROM departments");
                            while ($department = $departments->fetch_assoc()) {
                                echo "<option value='{$department['id']}'>{$department['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

<!-- Position -->
<div class="form-group">
    <label for="edit_position">Position</label>
    <select name="id_dp" id="edit_position" class="form-control" required>
        <option value="">Select Position</option>
        <!-- Options will be dynamically loaded -->
    </select>
</div>

                <div class="modal-footer">
                    <button type="submit" name="edit_user" class="btn btn-success">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $(".archive-user").click(function() {
        var user_id = $(this).data("id");

        if (confirm("Are you sure you want to archive this user?")) {
            $.ajax({
                url: "function/archive_user.php",
                type: "POST",
                data: { user_id: user_id },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        alert(response.message);
                        location.reload(); // Refresh the page after archiving
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function() {
                    alert("Failed to connect to the server.");
                }
            });
        }
    });
});
</script>



<?php include 'footer.php'; ?>