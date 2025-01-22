<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

$limit = 10; // Number of users per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Fetch total records with active condition
$totalQuery = "SELECT COUNT(id) AS total FROM users WHERE role != 'admin' AND active = 1";
if (!empty($search)) {
    $totalQuery .= " AND (firstname LIKE '%$search%' OR lastname LIKE '%$search%')";
}
$totalResult = $conn->query($totalQuery);
$totalUsers = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $limit);

// Fetch users with pagination
$query = "SELECT id, firstname, lastname, email, active FROM users WHERE role != 'admin' AND active = 1";
if (!empty($search)) {
    $query .= " AND (firstname LIKE '%$search%' OR lastname LIKE '%$search%')";
}
$query .= " LIMIT $start, $limit";
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
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
    <br>
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-primary text-white d-flex align-items-center">
                        <i class="fas fa-key fa-lg mr-2"></i>
                        <h3 class="card-title m-0">Change User Password</h3>
                    </div>
                    <div class="card-body">
                        <!-- Search Bar -->
                        <form method="GET" action="" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search by First Name or Last Name" value="<?= htmlspecialchars($search) ?>">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <a href="user_change_password.php" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                                </div>
                            </div>
                        </form>

                        <!-- User Table -->
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php $count = $start + 1; while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $count++; ?></td>
                                        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm reset-password" data-id="<?= $row['id'] ?>">
                                                <i class="fas fa-key"></i> Reset Password
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center">No users found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= htmlspecialchars($search) ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= htmlspecialchars($search) ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>

                        <!-- Back Button -->
                        <div class="d-flex justify-content-start mt-3">
                            <a href="manage_users.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<!-- Unbypassable Loading Modal -->
<div class="modal fade" id="loadingModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body">
                <h5 class="mb-3">Processing Password Reset...</h5>
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
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
<script>
$(document).ready(function() {
    $(".reset-password").click(function() {
        let user_id = $(this).data("id");

        $("#loadingModal").modal({
            backdrop: "static",
            keyboard: false
        }).modal("show");

        $.ajax({
            url: "change_password.php",
            type: "POST",
            data: { user_id: user_id },
            success: function(response) {
                $("#loadingModal").modal("hide");
                $("#successModal").modal("show");
            },
            error: function() {
                $("#loadingModal").modal("hide");
                alert("Error: Failed to reset password. Please try again.");
            }
        });
    });

    $("#successCloseBtn").click(function() {
        $("#successModal").modal("hide");
        location.reload();
    });
});
</script>
<?php include 'footer.php'; ?>

</body>
</html>
