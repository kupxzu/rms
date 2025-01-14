<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header('Location: ../index.php');
    exit();
}
include '../includes/db.php'; // Database connection

// Fetch the count of borrowed books
$user_id = $_SESSION['user_id'];
$sql_borrowed = "SELECT COUNT(*) AS borrowed_count FROM transactions WHERE user_id = ? AND transaction_type = 'Borrow'";
$stmt_borrowed = $conn->prepare($sql_borrowed);
$stmt_borrowed->bind_param("i", $user_id);
$stmt_borrowed->execute();
$result_borrowed = $stmt_borrowed->get_result();
$borrowed_count = $result_borrowed->fetch_assoc()['borrowed_count'];

// Fetch the count of returned books
$sql_returned = "SELECT COUNT(*) AS returned_count FROM transactions WHERE user_id = ? AND transaction_type = 'Return'";
$stmt_returned = $conn->prepare($sql_returned);
$stmt_returned->bind_param("i", $user_id);
$stmt_returned->execute();
$result_returned = $stmt_returned->get_result();
$returned_count = $result_returned->fetch_assoc()['returned_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
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

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $borrowed_count; ?></h3>
                                <p>Borrowed Books</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $returned_count; ?></h3>
                                <p>Returned Books</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book-reader"></i>
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
</body>
</html>
