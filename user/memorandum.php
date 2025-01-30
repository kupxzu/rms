
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header('Location: ../index.php');
    exit();
}
include '../includes/db.php'; // Database connection

$user_id = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        <div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">Uploaded Memorandums</h3>
        </div>
        <div class="card-body">
            <ul id="memorandum-list" class="list-group">
                <?php
                // Fetch all memorandums uploaded by VIPs
                $mem_sql = "SELECT m.*, v.name AS uploader_name, v.position AS uploader_position 
                            FROM memorandums m
                            JOIN vip_users v ON m.vip_id = v.id
                            ORDER BY m.uploaded_at DESC";
                $mem_stmt = $conn->prepare($mem_sql);
                $mem_stmt->execute();
                $mem_result = $mem_stmt->get_result();

                if ($mem_result->num_rows > 0) {
                    while ($mem = $mem_result->fetch_assoc()) {
                        echo "<li class='list-group-item'>
                                <strong>{$mem['title']}</strong> 
                                <br>
                                <small>{$mem['description']}</small>
                                <br>
                                <small class='text-muted'>Uploaded by: <b>{$mem['uploader_name']} ({$mem['uploader_position']})</b> on {$mem['uploaded_at']}</small>
                                <br>
                                <a href='{$mem['attachment']}' target='_blank' class='btn btn-sm btn-primary mt-1'><i class='fas fa-file-alt'></i> Open File</a>
                              </li>";
                    }
                } else {
                    echo "<li class='list-group-item text-muted'>No memorandums available.</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div>

    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function () {
  // Get the current URL
  const currentUrl = window.location.href;

  // Find all links in the sidebar
  $('.nav-link').each(function () {
    const link = $(this).attr('href');

    // Check if the current URL matches the link
    if (currentUrl.includes(link)) {
      // Add 'active' class to the clicked link
      $(this).addClass('active');

      // If it's inside a dropdown, ensure the dropdown is open
      $(this).closest('.has-treeview').addClass('menu-open');
      $(this).closest('.has-treeview').children('a').addClass('active');
    }
  });
});


</script>
</body>
</html>
