<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch Total Users Count
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];

// Fetch Ordinances and Resolutions Status Counts
$statuses = ['Pending', 'Approved', 'Rejected'];
$status_counts = [];
foreach ($statuses as $status) {
    $count_ordinances = $conn->query("SELECT COUNT(*) AS count FROM ordinances WHERE status = '$status'")->fetch_assoc()['count'];
    $count_resolutions = $conn->query("SELECT COUNT(*) AS count FROM resolutions WHERE status = '$status'")->fetch_assoc()['count'];
    $status_counts[$status] = $count_ordinances + $count_resolutions;
}

// Fetch Users Per Department
$department_users = $conn->query("SELECT d.name AS department, COUNT(u.id) AS user_count 
                                  FROM department_position dp 
                                  JOIN users u ON u.id_dp = dp.id
                                  JOIN departments d ON dp.department_id = d.id
                                  GROUP BY d.id");

$department_labels = [];
$department_data = [];
while ($row = $department_users->fetch_assoc()) {
    $department_labels[] = $row['department'];
    $department_data[] = $row['user_count'];
}

// Fetch Ordinance & Resolution Counts for Users
$submission_counts = $conn->query("
    SELECT 
        COUNT(o.id) AS ordinances_count,
        COUNT(r.id) AS resolutions_count
    FROM ordinances o
    LEFT JOIN resolutions r ON r.submitted_by = o.submitted_by
");

$ordinance_count = 0;
$resolution_count = 0;

if ($row = $submission_counts->fetch_assoc()) {
// Fetch the correct count of Ordinances and Resolutions
$ordinance_count = $conn->query("SELECT COUNT(*) AS count FROM ordinances")->fetch_assoc()['count'];
$resolution_count = $conn->query("SELECT COUNT(*) AS count FROM resolutions")->fetch_assoc()['count'];

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Welcome LGU Admin</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $total_users; ?></h3>
                                <p>Total Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart for Status -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">File Status Distribution</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="statusPieChart" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ordinance & Resolution Submissions</h3>
        </div>
        <div class="card-body">
            <canvas id="submissionPieChart" style="height:250px"></canvas>
        </div>
    </div>
</div>



                    <!-- Line Chart for User Departments -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Users Per Department</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="departmentLineChart" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table for File Status -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">File Status Summary</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-warning">
                                            <td>Pending</td>
                                            <td><?php echo $status_counts['Pending']; ?></td>
                                        </tr>
                                        <tr class="table-success">
                                            <td>Approved</td>
                                            <td><?php echo $status_counts['Approved']; ?></td>
                                        </tr>
                                        <tr class="table-danger">
                                            <td>Rejected</td>
                                            <td><?php echo $status_counts['Rejected']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
    // Pie Chart for Approved, Pending, Rejected
    const statusData = [<?php echo $status_counts['Pending']; ?>, <?php echo $status_counts['Approved']; ?>, <?php echo $status_counts['Rejected']; ?>];
    const statusLabels = ['Pending', 'Approved', 'Rejected'];
    
    new Chart(document.getElementById('statusPieChart'), {
        type: 'pie',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: ['#f39c12', '#00a65a', '#f56954'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // Line Chart for Users Per Department
    new Chart(document.getElementById('departmentLineChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($department_labels); ?>,
            datasets: [{
                label: 'Users Count',
                data: <?php echo json_encode($department_data); ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });



// Pie Chart for Ordinance & Resolution Submissions
const submissionLabels = ['Ordinances', 'Resolutions'];
const submissionData = [<?php echo $ordinance_count; ?>, <?php echo $resolution_count; ?>];

new Chart(document.getElementById('submissionPieChart'), {
    type: 'pie',
    data: {
        labels: submissionLabels,
        datasets: [{
            data: submissionData,
            backgroundColor: ['#007bff', '#f39c12'], // Blue for Ordinances, Orange for Resolutions
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

</script>

</body>
</html>
