<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/db.php';

$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordinance Mailbox</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    
    <style>
        .sticky-chat {
            position: sticky;
            top: 20px;
        }
        .btn-reply.active {
        background-color: #007bff;
        color: white;
    }
    </style>
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
                        <h1>Ordinance Mailbox</h1>
                    </div>
                </div>
            </div>
        </section>

        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($errorMessage); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <section class="content">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <a href="compose_ordinance.php" class="btn btn-primary btn-block mb-3">
                        <i class="fas fa-edit"></i> Compose Ordinance
                    </a>
                    <div class="sticky-chat">
    <div class="card card-primary card-outline direct-chat direct-chat-primary">
    <div class="card-header">
    <h3 class="card-title">
        <span id="direct-chat-title">Reply</span>
    </h3>
    <div class="card-tools">
        <span id="new-messages-badge" class="badge badge-primary">0</span>
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
        </button>
    </div>
</div>

        <div class="card-body">
            <div id="direct-chat-messages" class="direct-chat-messages">
                <div class="text-center text-muted">Click "Reply" to start a conversation.</div>
            </div>
        </div>
        <div class="card-footer">
        <form id="direct-chat-form">
    <input type="hidden" id="related-item-id" name="related_item_id">
    <input type="hidden" id="related-item-type" name="related_item_type" value="ordinance">
    <input type="hidden" id="receiver-id" name="receiver_id" value="3"> <!-- Example receiver ID -->
    <div class="input-group">
        <input type="text" name="message" id="message-input" class="form-control" placeholder="Type a message..." required>
        <span class="input-group-append">
            <button type="submit" class="btn btn-primary">Send</button>
        </span>
    </div>
</form>

        </div>
    </div>
</div>
                </div>
                
                <!-- Inbox -->
                <div class="col-md-9">
                <div class="card">
    <div class="card-header">
        <h3 class="card-title">Your Attachments (Ordinances)</h3>
    </div>
    <div class="card-body">
        <table class="table table-hover" id="user-attachments-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Submission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $userId = $_SESSION['user_id']; // Current logged-in user ID
                $sql = "
                    SELECT 
                        o.id, o.title, o.attachment, o.status, o.submission_date
                    FROM ordinances o
                    WHERE o.submitted_by = $userId
                    ORDER BY submission_date DESC";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td>
                        <?php
                        $extension = strtolower(pathinfo($row['attachment'], PATHINFO_EXTENSION));
                        echo $extension === 'pdf' ? '<i class="fas fa-file-pdf"></i>' : '<i class="fas fa-file-image"></i>';
                        ?>
                        <?php echo $row['attachment']; ?>
                    </td>
                    <td>
                        <?php echo $row['status'] == 'Pending' ? '<span class="badge badge-warning">Pending</span>' : ($row['status'] == 'Approved' ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-danger">Rejected</span>'); ?>
                    </td>
                    <td><?php echo $row['submission_date']; ?></td>
                    <td>
                        <a href="../uploads/ordinance/<?php echo urlencode($row['attachment']); ?>" target="_blank" class="btn btn-info btn-sm" hidden>
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button class="btn btn-primary btn-sm btn-reply" data-id="<?php echo $row['id']; ?>" data-type="ordinance">
                            <i class="fas fa-eye"></i> View Reply
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

                <div class="card">
    <div class="card-header">
        <h3 class="card-title">All Attachments (Ordinances)</h3>
        <div class="card-tools">
            <div class="input-group input-group-sm">
                <input type="text" id="search-input" class="form-control" placeholder="Search...">
                <input type="date" id="date-filter" class="form-control ml-2">
                <div class="input-group-append">
                    <button id="filter-btn" class="btn btn-primary"><i class="fas fa-filter"></i></button>
                    <button id="reset-btn" class="btn btn-secondary ml-1">Reset</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover" id="attachments-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Attachment</th>
                    <th>Submitted By</th>
                    <th>Status</th>
                    <th>Submission Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "
                    SELECT 
                        o.id, o.title, o.attachment, o.status, o.submission_date, u.username AS submitted_by
                    FROM ordinances o
                    JOIN users u ON o.submitted_by = u.id
                    ORDER BY submission_date DESC";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td>
                        <?php
                        $extension = strtolower(pathinfo($row['attachment'], PATHINFO_EXTENSION));
                        echo $extension === 'pdf' ? '<i class="fas fa-file-pdf"></i>' : '<i class="fas fa-file-image"></i>';
                        ?>
                        <?php echo $row['attachment']; ?>
                    </td>
                    <td><?php echo $row['submitted_by']; ?></td>
                    <td>
                        <?php echo $row['status'] == 'Pending' ? '<span class="badge badge-warning">Pending</span>' : ($row['status'] == 'Approved' ? '<span class="badge badge-success">Approved</span>' : '<span class="badge badge-danger">Rejected</span>'); ?>
                    </td>
                    <td><?php echo $row['submission_date']; ?></td>
                    <td>
                        <a href="../uploads/ordinance/<?php echo urlencode($row['attachment']); ?>" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button class="btn btn-primary btn-sm btn-reply" data-id="<?php echo $row['id']; ?>" data-type="ordinance">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

                </div><!-- /.col-md-9 -->
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    
    $(document).ready(function () {
    $('.btn-reply').on('click', function () {
        const itemId = $(this).data('id');
        const itemType = $(this).data('type');
        const itemTitle = $(this).closest('tr').find('td:first').text(); 
        $('#related-item-id').val(itemId);
        $('#related-item-type').val(itemType);
        $('.btn-reply').removeClass('active'); 
        $(this).addClass('active'); 
        $('#direct-chat-title').text(`Chat for: ${itemTitle}`);
        $('#message-input, #direct-chat-form button').prop('disabled', false);
        fetchMessages(itemId, itemType);
    });

    function fetchMessages(itemId, itemType) {
        $.ajax({
            url: 'fetch_messages.php',
            method: 'GET',
            data: { item_id: itemId, item_type: itemType },
            success: function (response) {
                const messages = JSON.parse(response);
                const container = $('#direct-chat-messages');
                container.empty();

                if (messages.length === 0) {
                    container.append('<div class="text-center text-muted">No messages yet.</div>');
                    return;
                }

                messages.forEach(msg => {
                    const alignClass = msg.sender_id === <?php echo $_SESSION['user_id']; ?> ? 'right' : '';
                    const senderName = alignClass ? 'You' : msg.sender_name;
                    const timestamp = new Date(msg.sent_at).toLocaleString();

                    container.append(`
                        <div class="direct-chat-msg ${alignClass}">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name ${alignClass ? 'float-right' : 'float-left'}">${senderName}</span>
                                <span class="direct-chat-timestamp ${alignClass ? 'float-left' : 'float-right'}">${timestamp}</span>
                            </div>
                            <div class="direct-chat-text">${msg.message}</div>
                        </div>
                    `);
                });
            }
        });
    }

    $('#direct-chat-form').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        $.post('send_message.php', formData, function () {
            fetchMessages($('#related-item-id').val(), $('#related-item-type').val());
            $('#message-input').val(''); // Clear input
        });
    });
});

$(document).ready(function () {
    const tableRows = $('#attachments-table tbody tr');

    // Filter Function
    $('#filter-btn').on('click', function () {
        const searchValue = $('#search-input').val().toLowerCase();
        const dateValue = $('#date-filter').val();

        tableRows.each(function () {
            const title = $(this).find('td:eq(0)').text().toLowerCase();
            const submissionDate = $(this).find('td:eq(4)').text();

            const matchesSearch = !searchValue || title.includes(searchValue);
            const matchesDate = !dateValue || submissionDate === dateValue;

            $(this).toggle(matchesSearch && matchesDate);
        });
    });

    // Reset Filters
    $('#reset-btn').on('click', function () {
        $('#search-input').val('');
        $('#date-filter').val('');
        tableRows.show();
    });
});


</script>
</body>
</html>
