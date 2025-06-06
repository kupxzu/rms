<?php
session_start();
include '../../includes/db.php'; // Database connection

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Mayor' && $_SESSION['role'] !== 'ViceMayor')) {
    header("Location: ../../index.php");
    exit();
}


$sql = "SELECT m.*, v.name AS uploader_name 
        FROM memorandums m 
        JOIN vip_users v ON m.vip_id = v.id 
        ORDER BY m.uploaded_at DESC";
$result = $conn->query($sql);

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM vip_users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result1 = $stmt->get_result();
$user1 = $result1->fetch_assoc();


?>

<?php include 'sidebar.php'; ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Memorandums</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Uploaded Memorandums</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>File Type</th>
                                <th>Uploader</th>
                                <th>Uploaded At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                        <td><?= htmlspecialchars($row['description']) ?></td>
                                        <td><?= strtoupper($row['file_type']) ?></td>
                                        <td><?= htmlspecialchars($row['uploader_name']) ?></td>
                                        <td><?= date("F d, Y h:i A", strtotime($row['uploaded_at'])) ?></td>
                                        <td>
                                            <a href="../../uploads/memorandums/<?= $row['attachment'] ?>" target="_blank" class="btn btn-info btn-sm">View</a>
                                            <a href="../../uploads/memorandums/<?= $row['attachment'] ?>" download class="btn btn-success btn-sm">Download</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No memorandums uploaded.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
