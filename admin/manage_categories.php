<?php
include '../includes/session.php';
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch categories
$sql = "SELECT * FROM categories";
$query = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
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
            <h1>Manage Categories</h1>
        </section>


        <?php include '../includes/notifications.php'; ?>


        <section class="content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCategoryModal">
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $query->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm edit" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-name="<?php echo $row['name']; ?>" 
                                                data-toggle="modal" 
                                                data-target="#editCategoryModal">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm delete" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-name="<?php echo $row['name']; ?>" 
                                                data-toggle="modal" 
                                                data-target="#deleteCategoryModal">
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

<?php include 'modals/add_category_modal.php'; ?>
<?php include 'modals/edit_category_modal.php'; ?>
<?php include 'modals/delete_category_modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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
    // Pass category data to Edit Modal
    $(document).on('click', '.edit', function() {
        $('#edit_category_id').val($(this).data('id'));
        $('#edit_category_name').val($(this).data('name'));
    });

    // Pass category data to Delete Modal
    $(document).on('click', '.delete', function() {
        $('#delete_category_id').val($(this).data('id'));
        $('#delete_category_name').text($(this).data('name'));
    });
</script>
</body>
</html>
