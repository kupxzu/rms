<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header('Location: ../index.php');
    exit();
}
include '../includes/db.php'; // Database connection

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
    <style>
        body {
            background-color: #f4f6f9;
        }
        .main-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            font-weight: bold;
        }
        .list-group-item {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .list-group-item:hover {
            background-color: #f0f0f0;
        }
        .upload-btn {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>

    <!-- Content Wrapper -->

        <div class="content-wrapper">
            <div class="container-fluid mt-3">
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="main-card">
                            <div class="row">
                                
                                <!-- File Upload Box (Now on the Left) -->
                                <div class="col-md-7">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h5>Upload File to: <span id="upload-to">Select a user</span></h5>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <form id="upload-form" action="function/upload_file.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="recipient_id" id="recipient-id">
                                                <div class="form-group">
                                                    <label>Title Request:</label>
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description (Optional):</label>
                                                    <textarea name="description" class="form-control"></textarea>
                                                </div>
                                                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="file-input" required disabled>
                    <label class="custom-file-label" for="file-input">Choose file</label>
                </div>
                                                <div class="upload-btn">
                                                    <button type="submit" class="btn btn-primary" id="upload-btn" disabled>Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="card-footer">
                                            <h6>Uploaded Files:</h6>
                                            <ul id="uploaded-files" class="list-group">
                                                <!-- Files will be loaded here -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- User List (Now on the Right) -->
                                <div class="col-md-5">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="card-title">Select Departments</h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="text" id="search-bar" class="form-control mb-2" placeholder="Search users..."> <br>
                                            <div id="users" class="list-group" style="max-height: 400px; overflow-y: auto;">
                                                <?php
                                                include '../includes/db.php';
                                                $user_id = $_SESSION['user_id'];

                                                $sql = "SELECT 
                                                            users.id, 
                                                            users.firstname, 
                                                            users.lastname, 
                                                            users.id_dp, 
                                                            positions.name AS position_name, 
                                                            departments.name AS department_name
                                                        FROM users
                                                        JOIN department_position ON users.id_dp = department_position.id
                                                        JOIN positions ON department_position.position_id = positions.id
                                                        JOIN departments ON department_position.department_id = departments.id
                                                        WHERE users.id != ?";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $user_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                while ($user = $result->fetch_assoc()) {
                                                    echo "<button class='list-group-item list-group-item-action user-item' data-id='{$user['id']}'>
                                                            <strong>{$user['firstname']} {$user['lastname']}</strong> 
                                                            <br><small>{$user['department_name']} <br> {$user['position_name']}</small>
                                                          </button>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- End Row -->
                        </div> <!-- End Main Card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
 
<script>
$(document).ready(function() {
    $(".user-item").click(function() {
        let userId = $(this).attr("data-id");

        if (!userId) {
            console.error("No user ID found.");
            return;
        }

        $("#recipient-id").val(userId);
        $("#upload-to").text($(this).text());
        $("#file-input").prop("disabled", false);
        $("#upload-btn").prop("disabled", false);

        console.log("Selected user ID:", userId);

        // Fetch uploaded files via AJAX
        $.ajax({
            url: "function/fetch_uploaded_files.php",
            type: "POST",
            data: { user_id: userId },
            dataType: "json",
            success: function(response) {
                console.log("Server Response:", response);

                let fileList = $("#uploaded-files");
                fileList.empty();

                if (response.error) {
                    console.error("Error:", response.error);
                    fileList.append("<li class='list-group-item text-danger text-center'>" + response.error + "</li>");
                    return;
                }

                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(file => {
                        let uploadedAt = new Date(file.uploaded_at).toLocaleString();
                        let alignment = (file.sender == userId) ? "text-left bg-light" : "text-right bg-info text-white";

                        fileList.append(
                            `<li class='list-group-item ${alignment}' style='border-radius: 10px; margin-bottom: 5px;'>
                                <b>${file.title} (${file.file_type})</b>
                                <br>
                                <small>${file.description ? file.description : 'No description'}</small>
                                <br>
                                <small class="text-muted">${uploadedAt}</small>
                                <br>
                                <a href="${file.attachment}" target="_blank" class="btn btn-sm btn-dark mt-1" style="background-color:rgb(153, 189, 228);">Open File</a>
                            </li>`
                        );
                    });
                } else {
                    fileList.append("<li class='list-group-item text-muted text-center'>No uploaded files</li>");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error);
                $("#uploaded-files").html("<li class='list-group-item text-danger text-center'>Failed to load files</li>");
            }
        });
    });
});


</script>
</body>
</html>
