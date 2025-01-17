<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch data for reports
$total_records = $conn->query("SELECT COUNT(*) AS count FROM records")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_transactions = $conn->query("SELECT COUNT(*) AS count FROM transactions")->fetch_assoc()['count'];

$recent_transactions = $conn->query(
    "SELECT transactions.id, records.title AS record, users.username AS user, transactions.transaction_type, transactions.transaction_date 
    FROM transactions 
    JOIN records ON transactions.record_id = records.id 
    JOIN users ON transactions.user_id = users.id 
    ORDER BY transactions.transaction_date DESC LIMIT 5"
);

// Fetch transaction types for pie chart
$transaction_types = $conn->query(
    "SELECT transaction_type, COUNT(*) AS count FROM transactions GROUP BY transaction_type"
);
$transaction_data = [];
while ($row = $transaction_types->fetch_assoc()) {
    $transaction_data[$row['transaction_type']] = $row['count'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Reports</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $total_records; ?></h3>
                            <p>Total Records</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $total_users; ?></h3>
                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $total_transactions; ?></h3>
                            <p>Total Transactions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Transactions</h3>
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
                                    <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Types</h3>
                </div>
                <div class="card-body">
                    <canvas id="transactionPieChart" style="height:250px"></canvas>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
    // Prepare data for pie chart
    const transactionData = <?php echo json_encode(array_values($transaction_data)); ?>;
    const transactionLabels = <?php echo json_encode(array_keys($transaction_data)); ?>;

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
