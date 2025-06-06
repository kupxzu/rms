<?php
session_start();
include '../includes/db.php';

// Fetch approved ordinances
$sql = "
    SELECT 
        o.id, 
        o.title, 
        o.attachment, 
        o.submission_date,
        IF(EXISTS(
            SELECT 1 
            FROM document_views 
            WHERE document_id = o.id 
            AND document_type = 'ordinance' 
            AND action = 'view'
        ), 'Yes', 'No') AS viewed,
        IF(EXISTS(
            SELECT 1 
            FROM document_views 
            WHERE document_id = o.id 
            AND document_type = 'ordinance' 
            AND action = 'download'
        ), 'Yes', 'No') AS downloaded
    FROM ordinances o
    WHERE o.status = 'Approved'
    ORDER BY o.submission_date DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordinance Bulletin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>

    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ordinance Bulletin</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Approved Ordinances</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                    <thead>
<tr>
    <th>Title</th>
    <th>Attachment Type</th>
    <th>Submission Date</th>
    <th>Viewed</th>
    <th>Downloaded</th>
    <th>Actions</th>
</tr>
<thead>
<tr>
    <th>Title</th>
    <th>Attachment Type</th>
    <th>Submission Date</th>
    <th>Viewed</th>
    <th>Downloaded</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo strtoupper(pathinfo($row['attachment'], PATHINFO_EXTENSION)); ?></td>
    <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
    <td><?php echo $row['viewed']; ?></td>
    <td><?php echo $row['downloaded']; ?></td>
    <td>
        <a href="../uploads/ordinance/<?php echo urlencode($row['attachment']); ?>" 
           target="_blank" 
           class="btn btn-info btn-sm" 
           onclick="trackAction(<?php echo $row['id']; ?>, 'ordinance', 'view')">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="../uploads/ordinance/<?php echo urlencode($row['attachment']); ?>" 
           download 
           class="btn btn-secondary btn-sm" 
           onclick="trackAction(<?php echo $row['id']; ?>, 'ordinance', 'download')">
            <i class="fas fa-download"></i> Download
        </a>
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

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="function/nav_function.js"></script>
<script>
function trackAction(documentId, documentType, action) {
    $.ajax({
        url: 'track_document_action.php',
        method: 'POST',
        data: {
            document_id: documentId,
            document_type: documentType,
            action: action
        },
        success: function (response) {
            console.log('Action tracked:', response);
        },
        error: function () {
            console.error('Failed to track action.');
        }
    });
}
</script>


</body>
</html>
