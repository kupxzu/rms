        <!-- Footer -->
        <footer class="main-footer text-center">
        <strong>VIP System &copy; <?= date("Y") ?>.</strong> All rights reserved.
    </footer>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
    // Sidebar Active Link Highlighting
    $(document).ready(function () {
        var path = window.location.pathname.split("/").pop();
        if (path == "") {
            path = "dashboard.php";
        }
        $(".nav-sidebar .nav-item .nav-link").each(function () {
            if ($(this).attr("href") === path) {
                $(this).addClass("active");
            }
        });
    });
</script>
</body>
</html>