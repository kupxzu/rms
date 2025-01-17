
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header('Location: ../index.php');
    exit();
}
include '../includes/db.php'; // Database connection

$user_id = $_SESSION['user_id'];



// Fetch user's attachments (Pending, Approved, Rejected)
$sql_attachments = "
SELECT 
    status, 
    COUNT(*) AS count 
FROM (
    SELECT status FROM ordinances WHERE submitted_by = ? 
    UNION ALL
    SELECT status FROM resolutions WHERE submitted_by = ?
) AS combined 
GROUP BY status";

$stmt_attachments = $conn->prepare($sql_attachments);
$stmt_attachments->bind_param("ii", $user_id, $user_id);
$stmt_attachments->execute();
$result_attachments = $stmt_attachments->get_result();

$attachments = ['Pending' => 0, 'Approved' => 0, 'Rejected' => 0];
while ($row = $result_attachments->fetch_assoc()) {
    $attachments[$row['status']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dashboard Stats -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">


                    <!-- Pending Attachments -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $attachments['Pending']; ?></h3>
                                <p>Pending Attachments</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Attachments -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?php echo $attachments['Approved']; ?></h3>
                                <p>Approved Attachments</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Attachments -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $attachments['Rejected']; ?></h3>
                                <p>Rejected Attachments</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphs -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Ordinances Status</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="ordinanceChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Resolutions Status</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="resolutionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>



<!-- Graphs Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ordinanceData = {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            label: 'Ordinances',
            data: [<?php echo $attachments['Pending']; ?>, <?php echo $attachments['Approved']; ?>, <?php echo $attachments['Rejected']; ?>],
            backgroundColor: ['#f39c12', '#007bff', '#dc3545']
        }]
    };

    const resolutionData = {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            label: 'Resolutions',
            data: [<?php echo $attachments['Pending']; ?>, <?php echo $attachments['Approved']; ?>, <?php echo $attachments['Rejected']; ?>],
            backgroundColor: ['#f39c12', '#007bff', '#dc3545']
        }]
    };

    const ctxOrdinance = document.getElementById('ordinanceChart').getContext('2d');
    new Chart(ctxOrdinance, { type: 'bar', data: ordinanceData });

    const ctxResolution = document.getElementById('resolutionChart').getContext('2d');
    new Chart(ctxResolution, { type: 'bar', data: resolutionData });
});
</script>

</body>
</html>
