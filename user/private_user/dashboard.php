<?php
session_start();
include '../../includes/db.php'; // Database connection

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Mayor' && $_SESSION['role'] !== 'ViceMayor')) {
    header("Location: ../../index.php"); // Redirect if not VIP
    exit();
}

// Fetch VIP user info
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">VIP User Info</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Name:</strong> <?= htmlspecialchars($user1['name']) ?></p>
                                <p><strong>Position:</strong> <?= htmlspecialchars($user1['position']) ?></p>
                                <p><strong>Username:</strong> <?= htmlspecialchars($user1['username']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h3 class="card-title">Recent Activity</h3>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">No recent activity</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include 'footer.php'; ?>
 