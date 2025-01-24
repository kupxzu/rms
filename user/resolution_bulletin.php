<?php
session_start();
include '../includes/db.php';

// Fetch approved resolutions
$userId = $_SESSION['user_id'];
$sql = "
    SELECT 
        o.id, 
        o.title,
        o.send_attachment as attachment,
        o.submission_date,
        MAX(CASE WHEN dv.action = 'view' THEN 1 ELSE 0 END) AS viewed,
        MAX(CASE WHEN dv.action = 'download' THEN 1 ELSE 0 END) AS downloaded
    FROM resolutions o
    LEFT JOIN document_views dv 
        ON o.id = dv.document_id 
        AND dv.document_type = 'resolution' 
        AND dv.user_id = $userId
    WHERE o.status = 'Approved'
    GROUP BY o.id
";


$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolution Bulletin</title>
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
                        <h1>Resolution Bulletin</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="badge badge-success" style="font-size: 20px;">Approved Resolution</h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover">

                    <thead>
<tr>
    <th>Title</th>
    <th>Attachment Type</th>
    <th>Submission Date</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()) { 
    $viewed = $row['viewed'] > 0; 
    $downloaded = $row['downloaded'] > 0; 
?>
<tr>
    <td><?php echo htmlspecialchars($row['title']); ?></td>
    <td><?php echo strtoupper(pathinfo($row['attachment'], PATHINFO_EXTENSION)); ?></td>
    <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
    <td>
    <a href="../uploads/resolution/<?php echo urlencode($row['attachment']); ?>" 
       target="_blank" 
       class="btn btn-<?php echo $row['viewed'] ? 'success' : 'info'; ?> btn-sm view-btn" 
       onclick="trackAction(<?php echo $row['id']; ?>, 'resolution', 'view', this)">
        <i class="fas fa-<?php echo $row['viewed'] ? 'check' : 'eye'; ?>"></i>
        <?php echo $row['viewed'] ? 'Viewed' : 'View'; ?>
    </a>
    <a href="../uploads/resolution/<?php echo urlencode($row['attachment']); ?>" 
       download 
       class="btn btn-<?php echo $row['downloaded'] ? 'success' : 'secondary'; ?> btn-sm download-btn" 
       onclick="trackAction(<?php echo $row['id']; ?>, 'resolution', 'download', this)">
        <i class="fas fa-<?php echo $row['downloaded'] ? 'check' : 'download'; ?>"></i>
        <?php echo $row['downloaded'] ? 'Opened' : 'Download'; ?>
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
function trackAction(documentId, documentType, action, button) {
    $.ajax({
        url: 'track_document_action.php',
        method: 'POST',
        data: {
            document_id: documentId,
            document_type: documentType,
            action: action
        },
        success: function (response) {
            const result = JSON.parse(response);
            if (result.success) {
                if (action === 'view') {
                    $(button).removeClass('btn-info').addClass('btn-success');
                    $(button).html('<i class="fas fa-check"></i> Viewed');
                } else if (action === 'download') {
                    $(button).removeClass('btn-secondary').addClass('btn-success');
                    $(button).html('<i class="fas fa-check"></i> Opened');
                }
            } else {
                alert('Failed to log the action: ' + result.error);
            }
        },
        error: function () {
            alert('An error occurred. Please try again.');
        }
    });
}

</script>


</body>
</html>
