<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="edit_user.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="form-group">
                        <label for="edit_id_dp">Department and Position</label>
                        <select name="id_dp" id="edit_id_dp" class="form-control" required>
                            <option value="">Unassigned</option>
                            <?php
                            $dp_result = $conn->query("SELECT dp.id, d.name AS department_name, p.name AS position_name 
                                                       FROM department_position dp
                                                       JOIN departments d ON dp.department_id = d.id
                                                       JOIN positions p ON dp.position_id = p.id");
                            while ($dp_row = $dp_result->fetch_assoc()) {
                                echo "<option value='{$dp_row['id']}'>{$dp_row['department_name']} - {$dp_row['position_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_user" class="btn btn-success">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
