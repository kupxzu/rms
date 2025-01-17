<?php
session_start();
include '../includes/db.php';

// Fetch pending resolutions
$sql = "SELECT resolutions.*, users.username 
        FROM resolutions 
        JOIN users ON resolutions.submitted_by = users.id 
        WHERE resolutions.status = 'Pending'";
$result = $conn->query($sql);

$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;

// Clear session messages
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Resolutions</title>
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Custom Styles -->
    <style>
        /* Enhance table font styling */
        .table {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            color: #333;
        }
        .table thead th {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .table tbody td {
            vertical-align: middle;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include 'navbar.php'; ?>
        <?php include 'sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <?php include 'function/toast_par.php'; ?>

            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Pending Resolutions</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resolutions Awaiting Approval</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Submitted By</th>
                                    <th>Submission Date</th>
                                    <th>Attachment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['title']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['submission_date']; ?></td>
                                        <td>
                                            <?php if ($row['attachment']): ?>
                                                <a class="btn btn-primary btn-sm" href="../uploads/resolution/<?php echo $row['attachment']; ?>" target="_blank">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a class="btn btn-secondary btn-sm" href="../uploads/resolutions/<?php echo $row['attachment']; ?>" download>
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            <?php else: ?>
                                                No Attachment
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="function/approve_reject_ordinance_resolution.php?type=resolution&id=<?php echo $row['id']; ?>&action=approve" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Approve
                                            </a>
                                            <button class="btn btn-danger btn-sm reject-item" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#rejectModal">
                                                <i class="fas fa-ban"></i> Reject
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

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="function/reject_item.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Resolution</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="item_id" id="reject_item_id">
                        <input type="hidden" name="item_type" value="resolution">
                        <div class="form-group">
                            <label for="rejection_title">Rejection Title</label>
                            <input type="text" name="rejection_title" id="rejection_title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="rejection_reason">Reason for Rejection</label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-ban"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <script>
        // Set the item ID when clicking the "Reject" button
        $(document).on('click', '.reject-item', function () {
            const itemId = $(this).data('id');
            $('#reject_item_id').val(itemId);
        });

        // Initialize toast notifications if present
        document.addEventListener('DOMContentLoaded', () => {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(toastEl => {
                const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                toast.show();
            });
        });
    </script>
</body>
</html>
