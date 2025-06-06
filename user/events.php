<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php'; 

$user_id = $_SESSION['user_id'];

// Fetch all "Events" files and check if the user has already viewed them
$query = "SELECT f.id, f.title, f.description, f.file_path, f.uploaded_at,
                 (SELECT COUNT(*) FROM file_views fv WHERE fv.file_id = f.id AND fv.user_id = ?) AS viewed
          FROM ordinances_resolutions f 
          WHERE f.file_type = 'Events' 
          ORDER BY f.uploaded_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>
<br><br>
    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-info text-white">
                  <h3 class="card-title">All Events</h3>
                </div>
                <div class="card-body">
                  <?php if ($result->num_rows > 0): ?>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Description</th>
                          <th>Uploaded At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                          <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                            <td><?= date("F d, Y", strtotime($row['uploaded_at'])) ?></td>
                            <td>
                            <a href="/<?= $file_path = htmlspecialchars($row['file_path']); ?>" target="_blank" 
                                 class="btn btn-sm <?= ($row['viewed'] > 0) ? 'btn-secondary' : 'btn-info' ?> view-file"
                                 data-file-id="<?= $row['id'] ?>">
                                <i class="fas fa-eye"></i> <?= ($row['viewed'] > 0) ? 'Viewed' : 'View File' ?>
                              </a>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  <?php else: ?>
                    <p class="text-muted">No events available.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
  <script src="function/nav_function.js"></script>


  <script>
    $(document).ready(function(){
        $(".view-file").click(function(){
            var fileId = $(this).data("file-id");
            var button = $(this);

            $.ajax({
                url: "record_view.php",
                type: "POST",
                data: { file_id: fileId },
                success: function(response) {
                    console.log("View recorded successfully");
                    button.removeClass("btn-info").addClass("btn-secondary").html('<i class="fas fa-eye"></i> Viewed');
                },
                error: function() {
                    console.log("Error recording view");
                }
            });
        });
    });
  </script>
<script>
function checkUnreadMessages() {
    $.ajax({
        url: "get_unread_messages.php",
        type: "GET",
        success: function(response) {
            let data = JSON.parse(response);
            let unreadCount = data.unread_count;

            if (unreadCount > 0) {
                $(".badge-danger").text(unreadCount).show();
            } else {
                $(".badge-danger").hide();
            }
        }
    });
}

// Refresh unread messages count every 5 seconds
setInterval(checkUnreadMessages, 5000);
</script>
</body>
</html>
