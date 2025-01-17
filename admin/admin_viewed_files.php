<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: ../user/dashboard.php');
    exit();
}
include '../includes/db.php';

// Fetch file views and downloads
$query = "SELECT 
            f.title AS file_title, 
            u.firstname, 
            u.lastname, 
            d.name AS department, 
            p.name AS position, 
            dv.action, 
            dv.action_timestamp
          FROM document_views dv 
          JOIN users u ON dv.user_id = u.id
          JOIN department_position dp ON u.id_dp = dp.id
          JOIN departments d ON dp.department_id = d.id
          JOIN positions p ON dp.position_id = p.id
          JOIN ordinances f ON dv.document_id = f.id
          WHERE dv.document_type = 'ordinance'
          UNION
          SELECT 
            f.title AS file_title, 
            u.firstname, 
            u.lastname, 
            d.name AS department, 
            p.name AS position, 
            dv.action, 
            dv.action_timestamp
          FROM document_views dv 
          JOIN users u ON dv.user_id = u.id
          JOIN department_position dp ON u.id_dp = dp.id
          JOIN departments d ON dp.department_id = d.id
          JOIN positions p ON dp.position_id = p.id
          JOIN resolutions f ON dv.document_id = f.id
          WHERE dv.document_type = 'resolution'
          ORDER BY action_timestamp DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch file creators with status
$creators_query = "SELECT o.id AS file_id, o.title, o.status AS status, u.firstname, u.lastname 
                   FROM ordinances o 
                   JOIN users u ON o.submitted_by = u.id
                   UNION
                   SELECT r.id AS file_id, r.title, r.status AS status, u.firstname, u.lastname 
                   FROM resolutions r 
                   JOIN users u ON r.submitted_by = u.id";
$creators_result = $conn->query($creators_query);

// Fetch login and logout history
$login_logout_query = "SELECT u.firstname, u.lastname, a.action, a.action_timestamp 
                      FROM user_activity a
                      JOIN users u ON a.user_id = u.id
                      ORDER BY a.action_timestamp DESC";
$login_logout_result = $conn->query($login_logout_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Viewed Files</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>
<br><br>
    <div class="content-wrapper">
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-10">
              <div class="card">
<div class="card-header p-2">
  <ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">File Views & Downloads</a></li>
    <button hidden class="btn btn-danger btn-sm ml-2" onclick="downloadPDF('fileViewsTable', 'File_Views_Downloads')">
      <i class="fas fa-file-pdf"></i> Download PDF
    </button>
    <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">File Creators with Status</a></li>
    <button hidden class="btn btn-danger btn-sm ml-2" onclick="downloadPDF('fileCreatorsTable', 'File_Creators_Status')">
      <i class="fas fa-file-pdf"></i> Download PDF
    </button>
    <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">User Login & Logout History</a></li>
    <button hidden class="btn btn-danger btn-sm ml-2" onclick="downloadPDF('userActivityTable', 'User_Activity_Log')">
      <i class="fas fa-file-pdf"></i> Download PDF
    </button>
  </ul>
</div>

                <div class="card-body">
                  <div class="tab-content">

                    <!-- File Views & Downloads -->
                    <div class="active tab-pane" id="activity">
                      <div class="card">
                        <div class="card-header bg-warning text-white">
                          <h3 class="card-title">    <button class="btn btn-danger btn-sm ml-2" onclick="downloadPDF('fileViewsTable', 'File_Views_Downloads')">
      <i class="fas fa-file-pdf"></i> 
    </button> File Views & Downloads</h3>
                        </div>
                        <div class="card-body">
                        <table id="fileViewsTable" class="table table-bordered">
  <thead>
    <tr>
      <th>File Title</th>
      <th>Viewed/Downloaded By</th>
      <th>Department</th>
      <th>Position</th>
      <th>Action</th>
      <th>Timestamp</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['file_title']) ?></td>
        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
        <td><?= htmlspecialchars($row['department']) ?></td>
        <td><?= htmlspecialchars($row['position']) ?></td>
        <td><span class="badge badge-<?= ($row['action'] === 'viewed') ? 'info' : 'success' ?>">
          <?= ucfirst($row['action']) ?></span>
        </td>
        <td><?= date("F d, Y - H:i:s", strtotime($row['action_timestamp'])) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

                        </div>
                      </div>
                    </div>

                    <!-- File Creators with Status -->
                    <div class="tab-pane" id="timeline">
                      <div class="card">
                        <div class="card-header bg-primary text-white">
                          <h3 class="card-title">    <button class="btn btn-danger btn-sm ml-2" onclick="downloadPDF('fileCreatorsTable', 'File_Creators_Status')">
      <i class="fas fa-file-pdf"></i>
    </button> File Creators with Status</h3>
                        </div>
                        <div class="card-body">
                        <table id="fileCreatorsTable" class="table table-bordered">
  <thead>
    <tr>
      <th>File Title</th>
      <th>Created By</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $creators_result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
        <td><span class="badge badge-<?= ($row['status'] === 'Pending') ? 'warning' : (($row['status'] === 'Approved') ? 'success' : 'danger') ?>">
          <?= ucfirst($row['status']) ?></span>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

                        </div>
                      </div>
                    </div>

                    <!-- User Login & Logout History -->
                    <div class="tab-pane" id="settings">
                      <div class="card">
                        <div class="card-header bg-dark text-white">
                          <h3 class="card-title">    <button class="btn btn-danger btn-sm ml-2" onclick="downloadPDF('userActivityTable', 'User_Activity_Log')">
      <i class="fas fa-file-pdf"></i>
    </button> User Login & Logout History</h3>
                        </div>
                        <div class="card-body">
                        <table id="userActivityTable" class="table table-bordered">
  <thead>
    <tr>
      <th>User</th>
      <th>Action</th>
      <th>Timestamp</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $login_logout_result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
        <td><span class="badge badge-<?= ($row['action'] === 'login') ? 'success' : 'danger' ?>">
          <?= ucfirst($row['action']) ?></span>
        </td>
        <td><?= date("F d, Y - H:i:s", strtotime($row['action_timestamp'])) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

                        </div>
                      </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>



<script>
  function downloadPDF(tableId, filename) {
      const { jsPDF } = window.jspdf;
      let doc = new jsPDF();

      // Add logo to PDF
      let img = new Image();
      img.src = 'www.png'; // Ensure the logo file is correctly placed
      doc.addImage(img, 'PNG', 80, 10, 50, 50); // Centered at the top

      // Title
      doc.setFontSize(14);
      doc.setFont("helvetica", "bold");
      doc.text("LGU Enrile, Tuguegarao Cagayan", 105, 70, { align: "center" });

      // Table Name
      doc.setFontSize(12);
      doc.setFont("helvetica", "normal");
      doc.text(filename.replace(/_/g, " "), 105, 80, { align: "center" });

      // Date
      let currentDate = new Date().toLocaleString();
      doc.setFontSize(10);
      doc.text("Date: " + currentDate, 14, 90);

      // Draw the table
      doc.autoTable({
          html: '#' + tableId,
          startY: 100,
          theme: 'grid',
          headStyles: { fillColor: [41, 128, 185] }, // Blue header
          alternateRowStyles: { fillColor: [240, 240, 240] },
          styles: { fontSize: 10, cellPadding: 2 }
      });

      // Save PDF
      doc.save(filename + ".pdf");
  }
</script>

</body>
</html>