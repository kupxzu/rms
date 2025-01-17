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


$(document).ready(function(){
    function updateNotificationCounts() {
        $.ajax({
            url: "fetch_notifications.php",
            type: "GET",
            success: function(data) {
                let response = JSON.parse(data);
                if (response.event_count > 0) {
                    $(".nav-item a[href='events.php'] .badge").text(response.event_count);
                } else {
                    $(".nav-item a[href='events.php'] .badge").text('');
                }

                if (response.notif_count > 0) {
                    $(".nav-item.has-treeview a[href='#'] .badge-warning").text(response.notif_count);
                } else {
                    $(".nav-item.has-treeview a[href='#'] .badge-warning").text('');
                }

                if (response.msg_count > 0) {
                    $(".nav-item a[href='incomming_message.php'] .badge").text(response.msg_count);
                } else {
                    $(".nav-item a[href='incomming_message.php'] .badge").text('');
                }
            }
        });
    }

    setInterval(updateNotificationCounts, 10000); // Refresh every 10 seconds
});
