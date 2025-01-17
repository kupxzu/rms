<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header('Location: ../index.php');
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT transactions.id, records.title AS record_title, transactions.transaction_date 
        FROM transactions 
        JOIN records ON transactions.record_id = records.id 
        WHERE transactions.user_id = ? AND transactions.transaction_type = 'Return' 
        ORDER BY transactions.transaction_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Borrowed Books</h1>
            </div>
        </section>

        <section class="content">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Book Title</th>
                        <th>Borrowed Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                        <td><?php echo $counter++; ?></td> 
                        <td><?php echo $row['record_title']; ?></td>
                        <td><?php echo date('F j, Y', strtotime($row['transaction_date'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
