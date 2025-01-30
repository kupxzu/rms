<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Mayor' && $_SESSION['role'] !== 'ViceMayor')) {
    header("Location: ../../index.php");
    exit();
}

// Pagination settings
$limit = 10;  // Number of cards per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch filter options
$deptQuery = "SELECT id, name FROM departments";
$deptResult = mysqli_query($conn, $deptQuery);

$typeQuery = "SELECT DISTINCT file_type FROM private_files";
$typeResult = mysqli_query($conn, $typeQuery);

// Fetch filtered files
$search = isset($_GET['search']) ? $_GET['search'] : "";
$department = isset($_GET['department']) ? $_GET['department'] : "";
$fileType = isset($_GET['file_type']) ? $_GET['file_type'] : "";

$query = "SELECT 
            private_files.id AS file_id,
            private_files.file_name,
            private_files.file_type,
            private_files.title,
            private_files.description,
            private_files.uploaded_at,
            users.firstname,
            users.lastname,
            departments.name AS department_name,
            positions.name AS position_name
          FROM private_files
          JOIN users ON private_files.uploaded_by = users.id
          JOIN department_position ON users.id_dp = department_position.id
          JOIN departments ON department_position.department_id = departments.id
          JOIN positions ON department_position.position_id = positions.id
          WHERE (private_files.title LIKE '%$search%' OR users.firstname LIKE '%$search%' OR users.lastname LIKE '%$search%')";

if (!empty($department)) {
    $query .= " AND departments.id = '$department'";
}

if (!empty($fileType)) {
    $query .= " AND private_files.file_type = '$fileType'";
}

$query .= " ORDER BY private_files.uploaded_at DESC LIMIT $start, $limit";
$result = mysqli_query($conn, $query);

// Count total files for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM private_files";
$totalResult = mysqli_query($conn, $totalQuery);
$totalFiles = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalFiles / $limit);


$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM vip_users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result1 = $stmt->get_result();
$user1 = $result1->fetch_assoc();
?>

<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Uploaded Files</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section inside a Card -->
    <div class="container-fluid">
        <div class="card p-4 shadow-sm mb-4" style="background: linear-gradient(to right, #ffffff, #f8f9fa); border-radius: 12px; border: 1px solid #ddd;">
            <form method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by title or uploader" value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="department" class="form-control">
                            <option value="">Filter by Department</option>
                            <?php while ($dept = mysqli_fetch_assoc($deptResult)) { ?>
                                <option value="<?= $dept['id'] ?>" <?= ($department == $dept['id']) ? "selected" : "" ?>><?= htmlspecialchars($dept['name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="file_type" class="form-control">
                            <option value="">Filter by File Type</option>
                            <?php while ($type = mysqli_fetch_assoc($typeResult)) { ?>
                                <option value="<?= $type['file_type'] ?>" <?= ($fileType == $type['file_type']) ? "selected" : "" ?>><?= htmlspecialchars($type['file_type']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Apply</button>
                    </div>
                    <div class="col-md-1">
                        <a href="private_files.php" class="btn btn-secondary w-100"><i class="fas fa-sync"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- File Cards inside a Styled Section -->
    <section class="content">
        <div class="container-fluid">
            <div class="card p-4 shadow-lg" style="background: linear-gradient(to right, #f9f9f9, #f2f2f2); border-radius: 12px; border: 1px solid #ccc;">
                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="col-md-4">
                            <div class="card shadow-lg mb-4" style="background: linear-gradient(to right, #ffffff, #f8f9fa); border-radius: 12px; border: 1px solid #ddd;">
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><?= htmlspecialchars($row['title']) ?></h5>
                                    <p class="text-muted"><br><br><strong>File Name:</strong> <?= htmlspecialchars($row['file_name']) ?></p>
                                    <p class="text-muted"><strong>Type:</strong> <?= htmlspecialchars($row['file_type']) ?></p>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                                    <hr>
                                    <p class="mb-1"><strong>Uploaded By:</strong> <?= htmlspecialchars($row['firstname'] . " " . $row['lastname']) ?></p>
                                    <p class="mb-1"><strong>Department & Position:</strong> <?= htmlspecialchars($row['department_name'] . " | " . $row['position_name']) ?></p>
                                    <p class="text-muted"><i class="fas fa-clock"></i> <?= htmlspecialchars($row['uploaded_at']) ?></p>
                                    <a href="function/download_private_file.php?file_id=<?= $row['file_id'] ?>" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Pagination inside the Card -->
                <div class="row">
                    <div class="col-md-12">
                        <nav>
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>&department=<?= htmlspecialchars($department) ?>&file_type=<?= htmlspecialchars($fileType) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<?php include 'footer.php'; ?>
