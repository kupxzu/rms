<?php
require '../includes/db.php'; // Ensure this connects to your database
$user_id = $_SESSION['user_id'] ?? 0; // Ensure user ID exists

// Fetch user details along with department and position
$query1 = "SELECT u.firstname, u.lastname, u.email, u.profile_pic, 
                 d.name AS department, p.name AS position
          FROM users u
          LEFT JOIN department_position dp ON u.id_dp = dp.id
          LEFT JOIN departments d ON dp.department_id = d.id
          LEFT JOIN positions p ON dp.position_id = p.id
          WHERE u.id = ?";

$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$user1 = $result1->fetch_assoc();

// Define profile picture with a fallback
$full_name = htmlspecialchars($user1['firstname'] . " " . $user1['lastname']);
$profile_picture = !empty($user1['profile_pic']) ? "../uploads/profile_pics/" . htmlspecialchars($user1['profile_pic']) : "../../dist/img/user4-128x128.jpg";

// ✅ Fetch unread messages count
$unreadCount = 0; // Default value to prevent undefined variable issues
$queryUnread = "SELECT COUNT(*) AS unread_count FROM messages_with_attachments WHERE receiver_id = ? AND is_read = 0";
$stmtUnread = $conn->prepare($queryUnread);
if ($stmtUnread) {
    $stmtUnread->bind_param("i", $user_id);
    $stmtUnread->execute();
    $resultUnread = $stmtUnread->get_result();
    if ($resultUnread) {
        $unreadData = $resultUnread->fetch_assoc();
        $unreadCount = $unreadData['unread_count'] ?? 0; // Ensure count is valid
    }
    $stmtUnread->close();
}
?>
<style>
.profile-user-img {
    width: 200px;
    height: 200px;
    object-fit: cover; /* Prevents stretching and zooms in */
    border-radius: 50%; /* Ensures a perfect circle */
    display: block;
    margin: 0 auto;
}

</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: rgb(44, 48, 73);">
  <!-- Brand Logo with Online Indicator -->
  <a href="dashboard.php" class="brand-link" style="position: relative;">
    <img src="../includes/www.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 40px; height: 40px;">
    <span class="brand-text font-weight-light">LGU Enrile</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Include global file for user details -->
    <!-- Sidebar User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img style="width: 40px; height: 40px;" src="<?= $profile_picture ?>" class="profile-user-img  elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">
          <?= htmlspecialchars($user1['firstname']) ?> <?= htmlspecialchars($user1['lastname']) ?> <br>
          <small style="color: #28a745; font-size: 11px;"><i class="fas fa-circle sm" class=""></i> online</small>
        </a>
      </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="events.php" class="nav-link">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>Events</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="memorandum.php" class="nav-link">
            <i class="nav-icon fas fa-file-text"></i>
            <p>Memorandums</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="ingoing.php" class="nav-link">
            <i class="nav-icon fas fa-comments"></i>
            <p>Request File</p>
          </a>
        </li>
        <li class="nav-item">
    <a href="u.php" class="nav-link">
        <i class="nav-icon fas fa-globe"></i>
        <p>
            Communication
            <?php if ($unreadCount > 0): ?>
                <span class="badge badge-danger"><?= $unreadCount ?></span>
            <?php endif; ?>
        </p>
    </a>
</li>


        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-bullhorn"></i>
            <p>From Admin <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="meeting.php" class="nav-link">
                <i class="far fa-circle nav-icon text-warning"></i>
                <p>Meeting Schedule</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="or_file.php" class="nav-link">
                <i class="far fa-circle nav-icon text-warning"></i>
                <p>Files</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-folder"></i>
            <p>Submit Documents <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="submit_ordinance.php" class="nav-link">
                <i class="far fa-circle nav-icon text-primary"></i>
                <p>Request Ordinance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="submit_resolution.php" class="nav-link">
                <i class="far fa-circle nav-icon text-primary"></i>
                <p>Request Resolution</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="upload_pf.php" class="nav-link">
                <i class="far fa-circle nav-icon text-danger"></i>
                <p>Upload Privite File</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-bullhorn"></i>
            <p>Bulletin <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="ordinance_bulletin.php" class="nav-link">
                <i class="far fa-circle nav-icon text-danger"></i>
                <p>Ordinance Bulletin</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="resolution_bulletin.php" class="nav-link">
                <i class="far fa-circle nav-icon text-danger"></i>
                <p>Resolution Bulletin</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>
