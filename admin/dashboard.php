<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch data for dashboard
$total_records = $conn->query("SELECT COUNT(*) AS count FROM records")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_categories = $conn->query("SELECT COUNT(*) AS count FROM categories")->fetch_assoc()['count'];
$total_transactions = $conn->query("SELECT COUNT(*) AS count FROM transactions")->fetch_assoc()['count'];

// Browser Usage Data for Pie Chart
$transaction_types = $conn->query("SELECT transaction_type, COUNT(*) AS count FROM transactions GROUP BY transaction_type");
$browser_data = [];
while ($row = $transaction_types->fetch_assoc()) {
    $browser_data[$row['transaction_type']] = $row['count'];
}

// Recently Added Transactions
$recent_transactions = $conn->query(
    "SELECT transactions.id, records.title AS record, users.username AS user, transactions.transaction_type, transactions.transaction_date 
    FROM transactions 
    JOIN records ON transactions.record_id = records.id 
    JOIN users ON transactions.user_id = users.id 
    ORDER BY transactions.transaction_date DESC LIMIT 5"
);
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
                    <div class="col-lg-3 col-6">
                        <!-- Small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $total_records; ?></h3>
                                <p>Records</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <a href="manage_records.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $total_users; ?></h3>
                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="manage_users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $total_categories; ?></h3>
                                <p>Categories</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <a href="manage_categories.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $total_transactions; ?></h3>
                                <p>Transactions</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <a href="manage_transactions.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Transaction Types</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="transactionPieChart" style="height:250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recently Added Transactions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recently Added Transactions</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Record</th>
                                            <th>User</th>
                                            <th>Transaction Type</th>
                                            <th>Transaction Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $recent_transactions->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['record']; ?></td>
                                                <td><?php echo $row['user']; ?></td>
                                                <td><?php echo $row['transaction_type']; ?></td>
                                                <td><?php echo date('F j, Y', strtotime($row['transaction_date'])); ?></td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
    $(function() {
        $('[data-widget="pushmenu"]').PushMenu();
    });

    // Prepare data for pie chart
    const transactionData = <?php echo json_encode(array_values($browser_data)); ?>;
    const transactionLabels = <?php echo json_encode(array_keys($browser_data)); ?>;

    // Render pie chart
    const ctx = document.getElementById('transactionPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: transactionLabels,
            datasets: [{
                data: transactionData,
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'top',
            },
        }
    });
</script>
</body>
</html>
