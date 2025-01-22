<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}
include '../includes/db.php';

// Pagination settings
$limit = 10; // Number of users per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Fetch total records count for pagination
$totalQuery = "SELECT COUNT(id) AS total FROM users WHERE active = 0";
if (!empty($search)) {
    $totalQuery .= " AND (username LIKE '%$search%' OR firstname LIKE '%$search%' OR lastname LIKE '%$search%')";
}
$totalResult = $conn->query($totalQuery);
$totalUsers = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $limit);

// Fetch archived users with search and pagination
$sql = "SELECT id, username, firstname, lastname, email FROM users WHERE active = 0";
if (!empty($search)) {
    $sql .= " AND (username LIKE '%$search%' OR firstname LIKE '%$search%' OR lastname LIKE '%$search%')";
}
$sql .= " LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Users</title>
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
                        <h1>Archived Users</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of Archived Users</h3>
                        <div class="card-tools">
                            <!-- Search Form -->
                            <form method="GET" action="archived_users.php">
                                <div class="input-group input-group-sm" style="width: 300px;">
                                    <input type="text" name="search" class="form-control float-right" 
                                           placeholder="Search by name or username..." 
                                           value="<?php echo htmlspecialchars($search); ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = $start + 1; while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                <td><?= $count++; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm restore-user" 
                                                data-id="<?php echo $row['id']; ?>">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <!-- Pagination Links -->
                        <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=1&search=<?php echo urlencode($search); ?>">First</a>
                            </li>
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">«</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">»</a>
                            </li>
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($search); ?>">Last</a>
                            </li>
                        </ul>

                        <div class="d-flex justify-content-start mt-3">
                            <a href="manage_users.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    $(".restore-user").on("click", function() {
        var userId = $(this).data("id");

        if (confirm("Are you sure you want to restore this user?")) {
            $.ajax({
                url: "function/restore_user.php",
                type: "POST",
                data: { user_id: userId },
                success: function(response) {
                    alert(response);
                    location.reload(); // Refresh the page after restoring
                },
                error: function() {
                    alert("Error restoring user.");
                }
            });
        }
    });
});
</script>
</body>
</html>
