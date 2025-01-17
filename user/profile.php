<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php'; // Ensure this connects to your database

$user_id = $_SESSION['user_id'];

// Fetch user details along with department and position
$query = "SELECT u.firstname, u.lastname, u.email, u.profile_pic, 
                 d.name AS department, p.name AS position
          FROM users u
          LEFT JOIN department_position dp ON u.id_dp = dp.id
          LEFT JOIN departments d ON dp.department_id = d.id
          LEFT JOIN positions p ON dp.position_id = p.id
          WHERE u.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$full_name = htmlspecialchars($user['firstname'] . " " . $user['lastname']);
$profile_picture = !empty($user['profile_pic']) ? "../uploads/profile_pics/" . htmlspecialchars($user['profile_pic']) : "../../dist/img/user4-128x128.jpg";
$department = htmlspecialchars($user['department'] ?? 'N/A');
$position = htmlspecialchars($user['position'] ?? 'N/A');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
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
<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>

    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- Profile Section -->
            <div class="col-md-3">
              <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                  <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
     src="<?= $profile_picture ?>"
     alt="User profile picture"
     data-toggle="modal" data-target="#profileModal"
     style="cursor: pointer; width: 200px; height: 200px; object-fit: cover;">

                  </div>
                  <h3 class="profile-username text-center"><?= $full_name ?></h3>
                  <p class="text-muted text-center"><?= htmlspecialchars($user['email']) ?></p>
                  <p class="text-muted text-center"><strong>Department:</strong> <?= $department ?></p>
                  <p class="text-muted text-center"><strong>Position:</strong> <?= $position ?></p>

                  <a href="../logout.php" class="btn btn-danger btn-block">
                    <i class="fas fa-sign-out-alt"></i> Logout
                  </a>
                </div>
              </div>
            </div>

            <!-- Settings Section -->
            <div class="col-md-9">
              <div class="card">
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Edit User</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="settings">
                      <form action="function/update_profile.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">

                        <div class="form-group">
                          <label for="firstname">First Name</label>
                          <input type="text" name="firstname" id="firstname" class="form-control" value="<?= htmlspecialchars($user['firstname']) ?>" required>
                        </div>

                        <div class="form-group">
                          <label for="lastname">Last Name</label>
                          <input type="text" name="lastname" id="lastname" class="form-control" value="<?= htmlspecialchars($user['lastname']) ?>" required>
                        </div>

                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div> 
        </div> 
      </section>
    </div>
  </div>

  <!-- Profile Picture Modal -->
  <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">Update Profile Picture</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <!-- Display Current Profile Picture -->
          <img src="<?= $profile_picture ?>" class="img-fluid rounded-circle mb-3"  style="cursor: pointer; width: 200px; height: 200px; object-fit: cover;">

          <!-- Change Profile Picture Form -->
          <form action="function/update_profile_pic.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <div class="form-group">
              <label for="new_profile_pic">Select New Profile Picture</label>
              
              <input type="file" name="new_profile_pic" id="new_profile_pic" class="form-control-file" accept=".jpg,.jpeg,.png" required>

              <br>
              <small class="form-text text-muted">Allowed formats: JPG, PNG (Max 2MB)</small>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Change</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
