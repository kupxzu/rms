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
    <title>Live Chat</title>
    
    <!-- Include AdminLTE UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
  <script src="function/nav_function.js"></script>
    <style>

    .chat-box {
        height: 400px; 
        overflow-y: auto; 
        padding: 10px; 
        background: #f8f9fa; 
        display: flex;
        flex-direction: column-reverse;
    }

    .message {
        padding: 10px; 
        border-radius: 8px; 
        margin-bottom: 10px; 
        max-width: 75%;
        position: relative;
    }

    .sent {
        background:rgb(103, 159, 219); 
        color: white; 
        align-self: flex-end;
    }

    .received {
        background: #e9ecef; 
        color: black; 
        align-self: flex-start;
    }

    .text-muted {
        font-size: 12px;
        display: block;
        margin-top: 5px;
        text-align: right;
    }

    .date-separator {
        font-size: 14px;
        font-weight: bold;
        padding: 5px 0;
        border-bottom: 1px solid #ccc;
    }

    .attachment-preview img { 
        max-width: 100px; border-radius: 5px; 
    }


    </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
<?php include '../includes/user_navbar.php'; ?>
<?php include '../includes/user_sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="container-fluid mt-3">
            <div class="row">
                
                <!-- User List -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title">Users</h5>
                        </div>
                        <div class="card-body">
                            <input type="text" id="search-bar" class="form-control mb-2" placeholder="Search users..."> <br>
                            <div id="users" class="list-group"></div>
                        </div>
                    </div>
                </div>

                <!-- Chat Box -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5>Chat with: <span id="chatting-with">Select a user</span></h5>
                        </div>
                        <div class="card-body chat-box d-flex flex-column" id="chat-box"></div>
                        <div class="card-footer">
    <div class="input-group">
        <input type="text" id="message-input" class="form-control" placeholder="Select a user first..." disabled>
        <input type="file" id="attachment-input" class="form-control" disabled>
        <div class="input-group-append">
            <button id="send-btn" class="btn btn-primary" disabled><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

                    </div>
                </div>

            </div> <!-- End Row -->
        </div>
    </div>
    </div>

<script>
$(document).ready(function () {
    var selectedUserId = null;

    // Initially disable chat input and send button
    $("#message-input, #attachment-input, #send-btn").prop("disabled", true);

    function loadUsers(searchQuery = "") {
        $.ajax({
            url: "uu.php?search=" + encodeURIComponent(searchQuery),
            type: "GET",
            success: function (response) {
                $("#users").html(""); // Clear user list before adding

                try {
                    var users = JSON.parse(response);
                    
                    if (users.length === 0) {
                        $("#users").html("<p>No users found.</p>");
                        return;
                    }

                    users.forEach(user => {
                        let unreadBadge = user.unread_count > 0 ? `<span class='badge badge-danger float-right'>${user.unread_count}</span>` : "";
                        let profilePic = user.profile_pic ? `../uploads/profile_pics/${user.profile_pic}` : "https://via.placeholder.com/50";

                        $("#users").append(`
                            <a href="#" class='list-group-item list-group-item-action user-item' data-id='${user.id}'>
                                <div class="d-flex align-items-center">
                                    <img src="${profilePic}" class="rounded-circle mr-2" width="60" height="60">
                                    <div>
                                        <strong style="font-size: 17px;">${user.firstname} ${user.lastname}</strong><br>
                                        <p><b>${user.position} </b><br> ${user.division}</p>
                                    </div>
                                    ${unreadBadge}
                                </div>
                            </a>
                        `);
                    });

                    $(".user-item").click(function () {
                        selectedUserId = $(this).data("id");
                        $("#chatting-with").text($(this).find("strong").text());

                        // Enable chatbox after selecting a user
                        $("#message-input, #attachment-input, #send-btn").prop("disabled", false);
                        $("#message-input").attr("placeholder", "Type a message...");

                        loadMessages();
                    });
                } catch (error) {
                    console.error("Error parsing users:", error);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error loading users:", error);
            }
        });
    }


    function loadMessages() {
        if (!selectedUserId) return;
        $.get("um.php?receiver_id=" + selectedUserId, function (data) {
            var messages = JSON.parse(data);
            $("#chat-box").html(""); // Clear chat box before adding messages

            var lastDate = "";

            messages.forEach(msg => {
                var classType = (msg.sender_id == <?php echo $_SESSION['user_id']; ?>) ? "sent" : "received";
                var attachmentHTML = "";
                var timeHTML = `<span class="text-muted small">${msg.formatted_time}</span>`;

                if (msg.attachment) {
                    var fileExtension = msg.attachment.split('.').pop().toLowerCase();
                    var fileUrl = "../uploads/messages/" + msg.attachment;

                    if (["jpg", "jpeg", "png", "gif"].includes(fileExtension)) {
                        attachmentHTML = `<br><img src="${fileUrl}" class="attachment-preview" style="max-width:200px; border-radius:5px;">`;
                    } else {
                        attachmentHTML = `<br><a href="${fileUrl}" target="_blank">📎 Download Attachment</a>`;
                    }
                }

                // Show date separator if the message is from a different day
                if (msg.formatted_date !== lastDate) {
                    $("#chat-box").append(`<div class="date-separator text-center text-muted my-2">${msg.formatted_date}</div>`);
                    lastDate = msg.formatted_date;
                }

                $("#chat-box").append(`
                    <div class='message ${classType} p-2'>
                        ${msg.message} ${attachmentHTML} 
                        <br><small class="text-muted">${timeHTML}</small>
                    </div>
                `);
            });

            // 🔥 Scroll to bottom after loading messages
            $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
        }).fail(function(xhr, status, error) {
            console.error("Error loading messages:", error);
        });
    }

    $("#send-btn").click(function () {
        var message = $("#message-input").val();
        var fileInput = $("#attachment-input")[0].files[0];

        if (!selectedUserId) {
            alert("Select a user to chat with.");
            return;
        }

        var formData = new FormData();
        formData.append("receiver_id", selectedUserId);
        formData.append("message", message);
        if (fileInput) {
            formData.append("attachment", fileInput);
        }

        $.ajax({
            url: "us.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $("#message-input").val("");
                $("#attachment-input").val("");
                loadMessages();
                loadUsers();
            }
        });
    });

    $("#search-bar").on("input", function () {
        loadUsers($(this).val());
    });

    setInterval(loadUsers, 5000);
    setInterval(loadMessages, 3000);
    loadUsers();
});
</script>

</body>
</html>
