<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).on('click', '.edit-user', function () {
        const userId = $(this).data('id');
        $('#edit_user_id').val(userId);

        // Fetch user data including department and position
        $.ajax({
            url: 'function/get_user_data.php',
            method: 'GET',
            data: { id: userId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#edit_firstname').val(response.data.firstname);
                    $('#edit_lastname').val(response.data.lastname);
                    $('#edit_age').val(response.data.age);
                    $('#edit_sex').val(response.data.sex);
                    $('#edit_contact').val(response.data.contact);
                    $('#edit_email').val(response.data.email);

                    // Populate department and trigger position load
                    $('#edit_department').val(response.data.department_id).change();

                    // Preselect the current position
                    setTimeout(() => {
                        $('#edit_position').val(response.data.id_dp);
                    }, 500); // Wait for positions to load
                } else {
                    alert('Failed to fetch user data.');
                }
            },
            error: function () {
                alert('An error occurred while fetching the user data.');
            }
        });
    });

    // Load positions dynamically when the department changes
    $('#edit_department').change(function () {
        const departmentId = $(this).val();
        $('#edit_position').html('<option value="">Select Position</option>');

        if (departmentId) {
            $.ajax({
                url: 'function/get_positions.php',
                method: 'GET',
                data: { department_id: departmentId },
                success: function (response) {
                    const positions = JSON.parse(response);
                    positions.forEach(pos => {
                        $('#edit_position').append(
                            `<option value="${pos.id}">${pos.name}</option>`
                        );
                    });
                }
            });
        }
    });
</script>






<script>
    // Fetch positions when a department is selected in the Add User modal
    $('#department').on('change', function () {
        const departmentId = $(this).val();

        if (departmentId) {
            $.ajax({
                url: 'function/get_positions.php', // Same file used for fetching positions dynamically
                type: 'GET',
                data: { department_id: departmentId },
                success: function (data) {
                    const positions = JSON.parse(data);
                    const positionDropdown = $('#position');
                    positionDropdown.empty();

                    positionDropdown.append('<option value="">Select Position</option>');
                    positions.forEach(position => {
                        positionDropdown.append(`<option value="${position.id}">${position.name}</option>`);
                    });
                }
            });
        } else {
            $('#position').empty().append('<option value="">Select Position</option>');
        }
    });
</script>


<script>
    $(function () {
        // Enable Treeview Toggle
        $('[data-widget="treeview"]').Treeview('toggle');
    });
</script>

</body>
</html>
