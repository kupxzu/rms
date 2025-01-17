<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "
    SELECT 
        o.id AS document_id,
        o.title,
        o.attachment,
        'ordinance' AS document_type,
        COALESCE(
            (SELECT action 
             FROM document_views dv 
             WHERE dv.user_id = $user_id 
               AND dv.document_id = o.id 
               AND dv.document_type = 'ordinance' 
             ORDER BY action_timestamp DESC LIMIT 1), 
            'Not Viewed'
        ) AS last_action
    FROM ordinances o
    WHERE o.status = 'Approved'
    UNION
    SELECT 
        r.id AS document_id,
        r.title,
        r.attachment,
        'resolution' AS document_type,
        COALESCE(
            (SELECT action 
             FROM document_views dv 
             WHERE dv.user_id = $user_id 
               AND dv.document_id = r.id 
               AND dv.document_type = 'resolution' 
             ORDER BY action_timestamp DESC LIMIT 1), 
            'Not Viewed'
        ) AS last_action
    FROM resolutions r
    WHERE r.status = 'Approved'
    ORDER BY document_id DESC;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</head>
<body>
<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Approved Ordinances and Resolutions</h3>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Attachment</th>
                            <th>Last Action</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo ucfirst($row['document_type']); ?></td>
                                <td>
                                    <?php
                                    $ext = strtolower(pathinfo($row['attachment'], PATHINFO_EXTENSION));
                                    $icon = $ext === 'pdf' ? 'fa-file-pdf' : 'fa-file-alt';
                                    ?>
                                    <i class="fas <?php echo $icon; ?>"></i> <?php echo htmlspecialchars($row['attachment']); ?>
                                </td>
                                <td><?php echo ucfirst($row['last_action']); ?></td>
                                <td>
                                    <button 
                                        class="btn btn-info btn-sm track-action" 
                                        data-id="<?php echo $row['document_id']; ?>" 
                                        data-type="<?php echo $row['document_type']; ?>" 
                                        data-action="view">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button 
                                        class="btn btn-secondary btn-sm track-action" 
                                        data-id="<?php echo $row['document_id']; ?>" 
                                        data-type="<?php echo $row['document_type']; ?>" 
                                        data-action="download">
                                        <i class="fas fa-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function () {
    $('.track-action').on('click', function () {
        const documentId = $(this).data('id');
        const documentType = $(this).data('type');
        const action = $(this).data('action');
        const button = $(this);

        $.post('track_document_action.php', {
            document_id: documentId,
            document_type: documentType,
            action: action
        }, function (response) {
            const result = JSON.parse(response);
            if (result.success) {
                alert(action.charAt(0).toUpperCase() + action.slice(1) + ' logged successfully!');
                location.reload();
            } else {
                alert('Error: ' + result.error);
            }
        });
    });
});
</script>
</body>
</html>
