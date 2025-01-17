<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
require '../includes/db.php';

$file_id = $_GET['id'];

// Fetch file details
$query = "SELECT * FROM ordinances_resolutions WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $file_id);
$stmt->execute();
$result = $stmt->get_result();
$file = $result->fetch_assoc();

// Fetch all departments
$dep_query = "SELECT id, name FROM departments";
$dep_result = $conn->query($dep_query);

// Fetch current departments that can view this file
$perm_query = "SELECT department_id FROM file_permissions WHERE file_id = ?";
$stmt = $conn->prepare($perm_query);
$stmt->bind_param("i", $file_id);
$stmt->execute();
$perm_result = $stmt->get_result();
$existing_departments = [];
while ($row = $perm_result->fetch_assoc()) {
    $existing_departments[] = $row['department_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Files</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>
<br><br>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h3 class="card-title">Edit Files</h3>
                        </div>
                        <div class="card-body">
                            <form action="update_file.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="file_id" value="<?= $file_id ?>">

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($file['title']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($file['description']) ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="file_type">File Type</label>
                                    <select name="file_type" id="file_type" class="form-control" required>
                                        <option value="None" <?= ($file['file_type'] == "None") ? 'selected' : '' ?>>None</option>
                                        <option value="Ordinance" <?= ($file['file_type'] == "Ordinance") ? 'selected' : '' ?>>Ordinance</option>
                                        <option value="Resolution" <?= ($file['file_type'] == "Resolution") ? 'selected' : '' ?>>Resolution</option>
                                        <option value="Events" <?= ($file['file_type'] == "Events") ? 'selected' : '' ?>>Events (All Departments)</option>
                                    </select>
                                </div>

                                <div id="departments-section" class="form-group">
                                    <label>Select Departments That Can View</label>
                                    <?php while ($department = $dep_result->fetch_assoc()): ?>
                                        <div class="form-check">
                                            <input type="checkbox" name="departments[]" value="<?= $department['id'] ?>" class="form-check-input"
                                                <?= in_array($department['id'], $existing_departments) ? 'checked' : '' ?>>
                                            <label class="form-check-label"><?= htmlspecialchars($department['name']) ?></label>
                                        </div>
                                    <?php endwhile; ?>
                                </div>

                                <div class="form-group">
                                    <label for="file">Replace File (Optional)</label>
                                    <input type="file" name="file" id="file" class="form-control-file" accept=".pdf,.doc,.docx,.png,.jpg">
                                    <small class="form-text text-muted">Leave blank if you do not wish to replace the existing file.</small>
                                </div>

                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function(){
    function toggleDepartments() {
        let fileType = $("#file_type").val();
        if (fileType === "Events") {
            $("#departments-section").hide();
        } else {
            $("#departments-section").show();
        }
    }

    $("#file_type").change(toggleDepartments);
    toggleDepartments();
});
</script>

</body>
</html>
