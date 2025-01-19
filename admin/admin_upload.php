<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
require '../includes/db.php';

// Fetch all departments
$query = "SELECT id, name FROM departments";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            margin-top: 40px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-control, .form-select {
            border-radius: 8px;
        }
        .btn-success {
            width: 100%;
            font-weight: bold;
            border-radius: 8px;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .form-check {
            margin-bottom: 8px;
        }
    </style>
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
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Upload Files</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            <?php endif; ?>
                            
                            <form action="upload_file.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="file_type">Action</label>
                                    <select name="file_type" id="file_type" class="form-control" required>
                                        <option value="None">Post Meeting</option>
                                        <option value="Ordinance">Send Ordinance File</option>
                                        <option value="Resolution">Send Resolution File
                                        
                                        </option>
                                        <option value="Events">Events (All Departments)</option>
                                    </select>
                                </div>

                                <div id="departments-section" class="form-group">
                                    <label>Select Departments That Can View</label>
                                    <div class="form-check">
                                        <input type="checkbox" id="select-all" class="form-check-input">
                                        <label class="form-check-label font-weight-bold">Publish to All?</label>
                                     
                                    </div>
                                    <br>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <div class="form-check">
                                            <input type="checkbox" name="departments[]" value="<?= $row['id'] ?>" class="form-check-input department-checkbox">
                                            <label class="form-check-label"><?= htmlspecialchars($row['name']) ?></label>
                                        </div>
                                    <?php endwhile; ?>
                                </div>

                                <div class="form-group">
                                    <label for="file">File Attachment</label>
                                    <input type="file" name="file" id="file" class="form-control-file" accept=".pdf,.doc,.docx,.png,.jpg" required>
                                </div>

                                <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Upload</button>
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

    // "Select All" Functionality
    $("#select-all").on("change", function() {
        $(".department-checkbox").prop("checked", this.checked);
    });

    // Uncheck "Select All" if a department is manually unchecked
    $(".department-checkbox").on("change", function() {
        if (!this.checked) {
            $("#select-all").prop("checked", false);
        }
    });
});

</script>

</body>
</html>
