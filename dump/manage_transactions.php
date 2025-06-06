<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch transactions
$sql = "SELECT transactions.id, records.title AS record, users.username AS user, transactions.transaction_type, transactions.transaction_date
        FROM transactions
        JOIN records ON transactions.record_id = records.id
        JOIN users ON transactions.user_id = users.id";
$query = $conn->query($sql);

// Fetch records and users for dropdowns
$records = $conn->query("SELECT * FROM records");
$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Transactions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Manage Transactions</h1>
        </section>
        <?php include '../includes/notifications.php'; ?>


        <section class="content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addTransactionModal">
                        <i class="fas fa-plus"></i> Add Transaction
                    </button>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $query->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['record']; ?></td>
                                    <td><?php echo $row['user']; ?></td>
                                    <td><?php echo $row['transaction_type']; ?></td>
                                    <td><?php echo date('F j, Y', strtotime($row['transaction_date'])); ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm edit" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-record="<?php echo $row['record']; ?>" 
                                                data-user="<?php echo $row['user']; ?>" 
                                                data-type="<?php echo $row['transaction_type']; ?>" 
                                                data-toggle="modal" 
                                                data-target="#editTransactionModal">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm delete" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-record="<?php echo $row['record']; ?>" 
                                                data-toggle="modal" 
                                                data-target="#deleteTransactionModal">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include 'modals/add_transaction_modal.php'; ?>
<?php include 'modals/edit_transaction_modal.php'; ?>
<?php include 'modals/delete_transaction_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            $(".alert").alert('close');
        }, 4000); // 4 seconds
    });
</script>

<script>
    // Pass transaction data to Edit Modal
    $(document).on('click', '.edit', function() {
        $('#edit_transaction_id').val($(this).data('id'));
        $('#edit_record').val($(this).data('record'));
        $('#edit_user').val($(this).data('user'));
        $('#edit_transaction_type').val($(this).data('type'));
    });

    // Pass transaction data to Delete Modal
    $(document).on('click', '.delete', function() {
        $('#delete_transaction_id').val($(this).data('id'));
        $('#delete_transaction_record').text($(this).data('record'));
    });
</script>
</body>
</html>
