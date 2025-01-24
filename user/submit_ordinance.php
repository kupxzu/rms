<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php'; // Ensure this file connects to your database

$user_id = $_SESSION['user_id'];

// Fetch pending ordinances submitted by the current user
$query = "SELECT id, title, description, submission_date, send_attachment, status 
          FROM ordinances 
          WHERE submitted_by = ? AND status = 'Pending' 
          ORDER BY submission_date DESC";

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
  <title>Compose Ordinance</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Compose Ordinance</h1>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-9">
              <div class="card">
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="nav-item">
                      <a class="nav-link active" href="#activity" data-toggle="tab">Submission</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#timeline" data-toggle="tab">View Submit</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <!-- Ordinance Form Tab -->
                    <div class="active tab-pane" id="activity">
                      <form action="function/submit_ordinance.php" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                          <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
                          </div>
                          <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter description" rows="4" required></textarea>
                          </div>
                          <div class="form-group">
                            <label for="attachment">Attachment</label>
                            <div class="custom-file">
                              <input type="file" name="attachment" id="attachment" class="custom-file-input" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required>
                              <label class="custom-file-label" for="attachment">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Allowed formats: PDF, DOCX, PNG, JPG</small>
                          </div>
                        </div>
                        <div class="card-footer">
                          <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit</button>
                          <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                      </form>
                    </div>

                    <!-- View Submit Tab - Displays Pending Ordinances -->
                    <div class="tab-pane" id="timeline">
                      <h5>Pending Ordinances</h5>
                      <?php if ($result->num_rows > 0): ?>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Title</th>
                              <th>Description</th>
                              <th>Submitted On</th>
                              <th>Attachment</th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                              <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                                <td><?= date("F d, Y", strtotime($row['submission_date'])) ?></td>
                                <td>
                                  <?php if ($row['attachment']): ?>
                                    <a class="btn btn-sm btn-info" href="../uploads/ordinance/<?= htmlspecialchars($row['attachment']) ?>" target="_blank"><i class="fas fa-eye"></i> View File</a>
                                  <?php else: ?>
                                    No Attachment
                                  <?php endif; ?>
                                </td>
                                <td><span class="badge badge-warning"><?= htmlspecialchars($row['status']) ?></span></td>
                              </tr>
                            <?php endwhile; ?>
                          </tbody>
                        </table>
                      <?php else: ?>
                        <p>No pending ordinances found.</p>
                      <?php endif; ?>
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

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
  <script src="function/nav_function.js"></script>
  <script>
    $(document).on('change', '.custom-file-input', function (e) {
      var fileName = e.target.files[0].name;
      $(this).next('.custom-file-label').html(fileName);
    });
  </script>
</body>
</html>
