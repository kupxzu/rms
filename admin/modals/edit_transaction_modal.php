<div class="modal fade" id="editTransactionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="edit_transaction.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_transaction_id" name="id">
                    <div class="form-group">
                        <label>Record</label>
                        <select id="edit_record" name="record_id" class="form-control" required>
                            <?php 
                            $record_query = $conn->query("SELECT * FROM records");
                            while ($record = $record_query->fetch_assoc()) {
                                echo "<option value='" . $record['id'] . "'>" . $record['title'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>User</label>
                        <select id="edit_user" name="user_id" class="form-control" required>
                            <?php 
                            $user_query = $conn->query("SELECT * FROM users");
                            while ($user = $user_query->fetch_assoc()) {
                                echo "<option value='" . $user['id'] . "'>" . $user['username'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Transaction Type</label>
                        <select id="edit_transaction_type" name="transaction_type" class="form-control" required>
                            <option value="Borrow">Borrow</option>
                            <option value="Return">Return</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>