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
  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    .sticky-chat {
      position: sticky;
      top: 20px;
    }
    .btn-reply.active {
      background-color: #007bff;
      color: white;
    }
    .direct-chat-messages {
      height: 300px;
      overflow-y: auto;
    }
    /* Added custom rule to push the row content slight right */
    .push-right {
      margin-left: 30px; /* Adjust this value as needed */
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Dummy Navbar and Sidebar -->
    <?php include '../includes/user_navbar.php'; ?>
    <?php include '../includes/user_sidebar.php'; ?>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Page Header -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Communication</h1>
            </div>
          </div>
        </div>
      </section>

      <!-- Main Content Section -->
      <section class="content">
        <!-- Added class "push-right" to push the row slightly to the right -->
        <div class="row push-right">
          <!-- Left Column: Chat Sidebar -->
          <div class="col-md-3">
            <a hidden href="#" class="btn btn-primary btn-block mb-3">
              <i class="fas fa-edit"></i> Compose Ordinance
            </a>
            <div class="sticky-chat">
  <div class="card card-primary card-outline direct-chat direct-chat-primary">
    <div class="card-header">
      <h3 class="card-title" id="direct-chat-title">Chat for: Select a User</h3>
      <div class="card-tools">
        <span hidden class="badge badge-primary" id="new-messages-badge">0 New Messages</span>
        <button type="button" class="btn btn-tool">
          <i class="fas fa-minus"></i>
        </button>
      </div>
    </div>
    <!-- Chat Messages -->
    <div class="card-body">
      <div class="direct-chat-messages" id="direct-chat-messages">
        <div class="text-center text-muted">No messages yet.</div>
      </div>
    </div>
    <!-- Chat Input -->
    <div class="card-footer">
      <form id="direct-chat-form" enctype="multipart/form-data">
        <input type="hidden" id="related-item-id" name="related_item_id">
        <input type="hidden" id="related-item-type" name="related_item_type" value="ordinance">
        <div class="input-group">
          <input type="text" name="message" id="message-input" class="form-control" placeholder="Type a message..." required>
          <input type="file" name="attachment" id="attachment-input" class="form-control-file btn" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx">
          <span class="input-group-append">
            <button type="submit" class="btn btn-primary">Send</button>
          </span>
        </div>
      </form>
    </div>
  </div>
</div>


          </div>
          
          <!-- Right Column: Search Ordinances -->
          <div class="col-md-7">
  <div class="card">
<div class="card-header">
  <!-- Search Bar -->
  <div class="input-group input-group-sm" style="width: 450px; height: 50px;">
    <input 
      style="width: 350px; height: 50px; font-size: 16px;" 
      type="text" id="search-bar" 
      class="form-control" 
      placeholder="Search by Username or Division">
    <div class="input-group-append">
      <button type="button" id="search-btn" style="width: 50px; height: 50px;" class="btn btn-default">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </div>

  <!-- Dropdown Search Results -->
  <div id="search-results" class="dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;">
  </div>
</div>

<div class="card-body">
  <!-- Messenger-like List -->
  <div class="list-group" id="messenger-list">
    <?php
    $currentUserId = $_SESSION['user_id']; // Get the current logged-in user ID

    $sql = "
    SELECT 
        u.id AS user_id,
        u.username,
        u.firstname,
        u.lastname,
        d.name AS division_name,
        p.name AS position_name,
        COUNT(CASE WHEN dm.is_read = 0 AND dm.receiver_id = $currentUserId THEN 1 END) AS unread_messages
    FROM users u
    LEFT JOIN department_position dp ON u.id_dp = dp.id
    LEFT JOIN departments d ON dp.department_id = d.id
    LEFT JOIN positions p ON dp.position_id = p.id
    LEFT JOIN messages_with_attachments dm 
        ON (dm.sender_id = u.id AND dm.receiver_id = $currentUserId) 
        OR (dm.receiver_id = u.id AND dm.sender_id = $currentUserId)
    WHERE u.id != $currentUserId
    AND u.role != 'admin'  -- 🛑 EXCLUDE ADMIN USERS
    GROUP BY u.id
    ORDER BY MAX(dm.sent_at) DESC;
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
    <div class="list-group-item">
      <div class="d-flex align-items-center">
        <!-- Profile Image -->
        <img src="profile.png" 
             alt="Profile Image" 
             class="rounded-circle mr-3" 
             style="width:50px; height:50px;">
        <!-- Content -->
        <div class="flex-grow-1">
          <h5 class="mb-1"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></h5>
          <p class="mb-1">
            <strong><?php echo htmlspecialchars($row['division_name'] ?? 'No Division'); ?></strong><br>
            <small><?php echo htmlspecialchars($row['position_name'] ?? 'No Position'); ?></small>
          </p>
          <?php if ($row['unread_messages'] > 0): ?>
            <span class="badge badge-primary"><?php echo $row['unread_messages']; ?> New Messages</span>
          <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="ml-auto">
          <button 
            class="btn btn-primary btn-sm btn-reply" 
            data-username="<?php echo htmlspecialchars($row['username']); ?>"
            data-user-id="<?php echo htmlspecialchars($row['user_id']); ?>">
            <i class="fas fa-reply"></i> Reply
          </button>
        </div>
      </div>
    </div>
    <?php 
        }
    } else {
        echo '<div class="text-center text-muted">No messages available.</div>';
    }
    ?>
  </div>
</div>


    </div>
  </div>
</div>


          </div><!-- /.col-md-7 -->
        </div>
      </section>
    </div>
  </div>
  <!-- JS Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
  <script>
 $(document).ready(function () {
    // Search functionality
    $('#search-btn').on('click', function () {
        const query = $('#search-bar').val().toLowerCase();
        $('.list-group-item').each(function () {
            const username = $(this).find('h5').text().toLowerCase();
            const division = $(this).find('strong').text().toLowerCase();
            if (username.includes(query) || division.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $(document).ready(function () {
    let currentReceiverId = null; // Tracks the currently active chat

    // Reply button functionality
    $('.btn-reply').on('click', function () {
        const username = $(this).data('username');
        const userId = $(this).data('user-id');

        $('#direct-chat-title').text(`Chat for: ${username}`);
        $('#related-item-id').val(userId);

        currentReceiverId = userId; // Set current active user
        fetchMessages(userId, true); // Fetch and mark messages as seen
        $(this).find('.badge-primary').remove(); // Remove the badge for new messages
    });

    // Fetch messages function
    function fetchMessages(receiverId, markAsSeen = false) {
        $.ajax({
            url: 'fetch_messages.php',
            method: 'GET',
            data: { receiver_id: receiverId, mark_as_seen: markAsSeen },
            success: function (response) {
                const messages = JSON.parse(response);
                const container = $('#direct-chat-messages');
                container.empty();

                if (messages.length === 0) {
                    container.append('<div class="text-center text-muted">No messages yet.</div>');
                    return;
                }

                messages.forEach((msg) => {
                    const alignClass = msg.sender === 'You' ? 'right' : '';
                    const attachmentPath = msg.attachment ? `../uploads/messages/${msg.attachment}` : null;
                    const attachmentLink = attachmentPath
                        ? `<a href="${attachmentPath}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Attachment</a>`
                        : '';
                    container.append(`
                        <div class="direct-chat-msg ${alignClass}">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name ${alignClass ? 'float-right' : 'float-left'}">${msg.sender}</span>
                                <span class="direct-chat-timestamp ${alignClass ? 'float-left' : 'float-right'}">${new Date(msg.sent_at).toLocaleString()}</span>
                            </div>
                            <div class="direct-chat-text">${msg.message}</div>
                            ${attachmentLink}
                        </div>
                    `);
                });

                container.scrollTop(container[0].scrollHeight); // Auto-scroll to the latest message
            },
            error: function () {
                console.error('Failed to fetch messages.');
            },
        });
    }

    // Send messages
    $('#direct-chat-form').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: 'send_message_with_attachment.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                if (currentReceiverId) fetchMessages(currentReceiverId); // Refresh messages after sending
                $('#message-input').val('');
                $('#attachment-input').val('');
            },
            error: function () {
                alert('Failed to send the message.');
            },
        });
    });

    // Polling for new messages
    setInterval(function () {
        if (currentReceiverId) {
            fetchMessages(currentReceiverId); // Fetch messages for the active user
        } else {
            updateUnreadBadges(); // Update unread badges for other users
        }
    }, 5000); // Poll every 5 seconds

    // Update unread message badges
    function updateUnreadBadges() {
        $.ajax({
            url: 'fetch_unread_badges.php',
            method: 'GET',
            success: function (response) {
                const unreadCounts = JSON.parse(response);
                $('.btn-reply').each(function () {
                    const userId = $(this).data('user-id');
                    const unreadCount = unreadCounts[userId] || 0;

                    $(this).find('.badge-primary').remove(); // Clear old badges
                    if (unreadCount > 0 && userId !== currentReceiverId) {
                        $(this).append(`<span class="badge badge-primary">${unreadCount} New Messages</span>`);
                    }
                });
            },
        });
    }
    
});
 });
  </script>
  <script>
$(document).ready(function () {
    // Live search and select user
    $("#search-bar").on("input", function () {
        const query = $(this).val().trim();
        if (query.length > 0) {
            $.ajax({
                url: "search_users.php",
                method: "POST",
                data: { search: query },
                success: function (response) {
                    $("#search-results").html(response).show();
                },
                error: function () {
                    $("#search-results").html('<div class="dropdown-item text-center text-muted">Error fetching results</div>').show();
                }
            });
        } else {
            $("#search-results").hide();
        }
    });

    // Handle Reply Button Click (From Search and User List)
    $(document).on("click", ".btn-reply", function () {
        const username = $(this).data("username");
        const userId = $(this).data("user-id");

        // Set chat title and update form fields
        $("#direct-chat-title").text(`Chat for: ${username}`);
        $("#related-item-id").val(userId);

        // Enable message input
        $("#message-input").prop("disabled", false);
        $("#direct-chat-form button").prop("disabled", false);

        // Fetch previous messages
        fetchMessages(userId);

        // Hide search dropdown
        $("#search-results").hide();
    });

    // Function to Fetch Messages for the Selected User
    function fetchMessages(receiverId) {
        $.ajax({
            url: "fetch_messages.php",
            method: "GET",
            data: { receiver_id: receiverId },
            success: function (response) {
                const messages = JSON.parse(response);
                const container = $("#direct-chat-messages");
                container.empty();

                if (messages.length === 0) {
                    container.append('<div class="text-center text-muted">No messages yet.</div>');
                    return;
                }

                messages.forEach((msg) => {
                    const alignClass = msg.sender === "You" ? "right" : "";
                    const attachment = msg.attachment
                        ? `<a href="${msg.attachment}" target="_blank" class="text-primary"><i class="fas fa-paperclip"></i> Attachment</a>`
                        : "";
                    container.append(`
                        <div class="direct-chat-msg ${alignClass}">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name ${alignClass ? "float-right" : "float-left"}">${msg.sender}</span>
                                <span class="direct-chat-timestamp ${alignClass ? "float-left" : "float-right"}">${new Date(msg.sent_at).toLocaleString()}</span>
                            </div>
                            <div class="direct-chat-text">${msg.message}</div>
                            ${attachment}
                        </div>
                    `);
                });

                container.scrollTop(container[0].scrollHeight);
            },
            error: function () {
                alert("Failed to fetch messages. Please try again.");
            },
        });
    }

    // Handle Message Send
    $("#direct-chat-form").on("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("receiver_id", $("#related-item-id").val());

        $.ajax({
            url: "send_message_with_attachment.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                fetchMessages($("#related-item-id").val());
                $("#message-input").val("");
                $("#attachment-input").val("");
            },
            error: function () {
                alert("Failed to send message.");
            },
        });
    });
});


  </script>
<script src="function/nav_function.js"></script>


</body>
</html>
