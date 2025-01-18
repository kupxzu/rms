<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch all users
$query = "SELECT id, firstname, lastname, email FROM users WHERE role != 'admin'";
$result = $conn->query($query);

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
<div class="wrapper">

<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>
<br>
<br>
<div class="content-wrapper">
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                        <i class="fas fa-key fa-lg mr-2"></i>
                        <h3 class="card-title m-0">Change User Password</h3>
                    </div>
                    <div class="card-body">
                        <!-- Success & Error Messages -->
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?= $_SESSION['success']; ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error']; ?>
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <!-- Password Change Form -->
                        <form id="change-password-form" action="change_password.php" method="POST">
                            <div class="form-group">
                                <label for="user_id"><i class="fas fa-user"></i> Select User</label>
                                <select name="user_id" id="user_id" class="form-control select2" required>
                                    <option value="">-- Select User --</option>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <option value="<?= $row['id'] ?>">
                                            <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['email'] . ')') ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="manage_users.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" id="submit-btn" class="btn btn-warning">
                                    <i class="fas fa-key"></i> Generate & Send New Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<!-- Loading Modal (Shown While Processing) -->
<div class="modal fade" id="loadingModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <h5 class="mb-3">Processing Password Change...</h5>
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal (Shown After Password Reset) -->
<div  class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <h5 class="mb-3 text-success"><i class="fas fa-check-circle"></i> Password Changed Successfully!</h5>
                <p>The new password has been sent to the user.</p>
                <button type="button" class="btn btn-success" id="successCloseBtn">OK</button>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
$(document).ready(function() {
    $("#change-password-form").submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        let form = $(this);
        let submitButton = $("#submit-btn");

        // Show the loading modal
        $("#loadingModal").modal("show");

        // Disable the submit button to prevent multiple clicks
        submitButton.prop("disabled", true);

        // Send the form data via AJAX
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize(),
            success: function(response) {
                // Hide the loading modal
                $("#loadingModal").modal("hide");

                // Show the success modal
                $("#successModal").modal("show");
            },
            error: function() {
                // Hide the loading modal and re-enable the submit button on error
                $("#loadingModal").modal("hide");
                submitButton.prop("disabled", false);
                alert("Error: Failed to change password. Please try again.");
            }
        });
    });

    // Close Success Modal, Re-enable Button, and Redirect
    $("#successCloseBtn").click(function() {
        $("#successModal").modal("hide");

        // Re-enable the submit button after success
        $("#submit-btn").prop("disabled", false);

        // Redirect after success
        window.location.href = "user_change_password.php"; 
    });
});
</script>



</body>
</html>
