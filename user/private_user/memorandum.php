<?php
session_start();
include '../../includes/db.php'; // Database connection

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Mayor' && $_SESSION['role'] !== 'ViceMayor')) {
    header("Location: ../../index.php"); // Redirect if not VIP
    exit();
}

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
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
        <div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="card-title">Upload Memorandum</h3>
        </div>
        <div class="card-body">
            <form id="memorandum-form" action="function/upload_memorandum.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description (Optional):</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Select File (PDF, DOCX, Image):</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Upload Memorandum</button>
            </form>
        </div>
    </div>
</div>
</section>
</div>
<?php include 'footer.php'; ?>
