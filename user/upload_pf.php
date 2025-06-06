<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header('Location: ../index.php');
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </section>

        <!-- Upload Private File Section -->
        <section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Private File &nbsp <a href="upload_pf.php" class="fas fa-cycle"><i class="fas fa-sync-alt"></i></a></h3>
            </div>
            <div class="card-body">
            <form id="uploadForm" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="custom-file">
        <input type="file" name="file" class="custom-file-input" required>
        <label class="custom-file-label" for="file">Choose File (PDF, Image, DOCX)</label>
    </div>
    <br><br>
    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</button>
</form>

            </div>
        </div>
    </div>
</section>

        <!-- Uploaded Files List -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Your Uploaded Files</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>File Name</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Uploaded At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM private_files WHERE uploaded_by = ? ORDER BY uploaded_at DESC";
                                $stmt = mysqli_prepare($conn, $query);
                                mysqli_stmt_bind_param($stmt, "s", $user_id);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                $count = 1;

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $count++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['file_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['file_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['uploaded_at']) . "</td>";
                                    echo "<td>
<button class='btn btn-sm btn-primary request-download' data-file-id='" . $row['id'] . "'>
    <i class='fas fa-download'></i> Download
</button>

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
</div>

<!-- Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="passwordModalLabel">Enter Password to Download</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="download-form">
                    <input type="hidden" id="file-id" name="file_id">
                    <div class="form-group">
                        <label for="password">Enter Your Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Verify & Download</button>
                </form>
                <div id="download-error" class="text-danger mt-2" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>


<!-- Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="infoModalLabel">File Submission Notice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Your file has been successfully uploaded and is directed to the Mayor or Vice Mayor for review.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
  <script src="function/nav_function.js"></script>
  <script>
$(document).ready(function() {
    $("#uploadForm").submit(function(event) {
        event.preventDefault(); // Prevent page reload

        var formData = new FormData(this);

        $.ajax({
            url: "upload_private_file.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2500
                    });
                    $("#uploadForm")[0].reset();
                    $(".custom-file-label").html("Choose File");
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Upload Failed",
                        text: response.message,
                        showConfirmButton: true
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An unexpected error occurred.",
                    showConfirmButton: true
                });
            }
        });
    });

    // Update file input label
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
});
</script>
  <script>
    $(document).on('change', '.custom-file-input', function (e) {
      var fileName = e.target.files[0].name;
      $(this).next('.custom-file-label').html(fileName);
    });
  </script>

<script>
$(document).ready(function () {
    $(".request-download").click(function () {
        let fileId = $(this).attr("data-file-id");
        $("#file-id").val(fileId);
        $("#passwordModal").modal("show");
    });

    $("#download-form").submit(function (event) {
        event.preventDefault();
        let fileId = $("#file-id").val();
        let password = $("#password").val();

        $.ajax({
            url: "function/verify_password.php",
            type: "POST",
            data: { file_id: fileId, password: password },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    window.location.href = "download_private_file.php?file_id=" + fileId;
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Incorrect Password",
                        text: response.message,
                        showConfirmButton: true
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An unexpected error occurred. Please try again.",
                    showConfirmButton: true
                });
            }
        });
    });
});
</script>


</body>
</html>
