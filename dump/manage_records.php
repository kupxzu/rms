<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch records and categories
$sql = "SELECT records.id, records.title, records.description, records.category_id, categories.name AS category 
        FROM records 
        LEFT JOIN categories ON records.category_id = categories.id";

$query = $conn->query($sql);

// Fetch categories for dropdowns
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Records</title>
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
            <h1>Manage Records</h1>
        </section>
        <?php include '../includes/notifications.php'; ?>

        <section class="content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRecordModal">
                        <i class="fas fa-plus"></i> Add Record
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $query->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['category'] ?: 'Uncategorized'; ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm edit" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-title="<?php echo $row['title']; ?>" 
                                                data-description="<?php echo $row['description']; ?>" 
                                                data-category="<?php echo $row['category_id']; ?>" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editRecordModal">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button class="btn btn-danger btn-sm delete" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-title="<?php echo $row['title']; ?>" 
                                                data-toggle="modal" data-target="#deleteRecordModal">
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

<?php include 'modals/add_record_modal.php'; ?>
<?php include 'modals/edit_record_modal.php'; ?>
<?php include 'modals/delete_record_modal.php'; ?>

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
    // Pass record data to Edit Modal
    $(document).on('click', '.edit', function() {
        $('#edit_record_id').val($(this).data('id'));
        $('#edit_title').val($(this).data('title'));
        $('#edit_description').val($(this).data('description'));
        $('#edit_category').val($(this).data('category'));
    });

    // Pass record data to Delete Modal
    $(document).on('click', '.delete', function() {
        $('#delete_record_id').val($(this).data('id'));
        $('#delete_record_title').text($(this).data('title'));
    });
</script>
</body>
</html>
