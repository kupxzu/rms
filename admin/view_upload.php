<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
require '../includes/db.php';

// Pagination settings
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search and filter functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : "";


// Build WHERE clause for search and filter
$whereClause = "1=1";
if (!empty($search)) {
    $whereClause .= " AND (title LIKE '%$search%' OR file_type LIKE '%$search%')";
}
if (!empty($filter) && ($filter == 'Ordinance' || $filter == 'Resolution' || $filter == 'None' || $filter == 'Events')) {
    $whereClause .= " AND file_type = '$filter'";
}

// Fetch total records count for pagination
$totalQuery = "SELECT COUNT(id) AS total FROM ordinances_resolutions WHERE $whereClause";
$totalResult = $conn->query($totalQuery);
$totalFiles = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalFiles / $limit);

// Fetch uploaded files with search, filter, and pagination
$query = "SELECT id, title, file_type, uploaded_at FROM ordinances_resolutions 
          WHERE $whereClause ORDER BY uploaded_at DESC LIMIT $start, $limit";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Uploaded Files</title>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white d-flex justify-content-between">
                            <h3 class="card-title">Uploaded Files</h3>
                            <!-- Search & Filter Form -->
                            <form method="GET" action="view_upload.php" class="d-flex">
                                <div class="input-group input-group-sm mr-2">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search title..." 
                                           value="<?php echo htmlspecialchars($search); ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>

                                <div class="input-group input-group-sm mr-2">
                                    <select name="filter" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Types</option>
                                        <option value="Ordinance" <?php if ($filter == 'Ordinance') echo 'selected'; ?>>Ordinance</option>
                                        <option value="Resolution" <?php if ($filter == 'Resolution') echo 'selected'; ?>>Resolution</option>
                                        <option value="Events" <?php if ($filter == 'Events') echo 'selected'; ?>>Events</option>
                                        <option value="None" <?php if ($filter == 'None') echo 'selected'; ?>>Meeting</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Uploaded At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['title']) ?></td>
                                            <td><?= htmlspecialchars($row['file_type']) ?></td>
                                            <td><?= date("F d, Y", strtotime($row['uploaded_at'])) ?></td>
                                            <td>
                                                <a href="edit_file.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer">
                            <!-- Pagination Links -->
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=1&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">First</a>
                                </li>
                                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">«</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">»</a>
                                </li>
                                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">Last</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Success Modal -->
<div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center p-4">
            <div class="modal-body">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <strong id="successMessage"></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function(){
    // Check if session success message exists
    <?php if (isset($_SESSION['success'])): ?>
        $("#successMessage").text("<?= $_SESSION['success'] ?>");
        $("#successModal").modal("show");

        // Auto-hide modal after 3 seconds
        setTimeout(function() {
            $("#successModal").modal("hide");
        }, 3000);

        <?php unset($_SESSION['success']); // Clear message after showing ?>
    <?php endif; ?>
});
</script>

</body>
</html>
