<div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="add_transaction.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: red " >&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Record</label>
                        <select name="record_id" class="form-control" required>
                            <?php while ($rec = $records->fetch_assoc()) { ?>
                                <option value="<?php echo $rec['id']; ?>"><?php echo $rec['title']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>User</label>
                        <select name="user_id" class="form-control" required>
                            <?php while ($user = $users->fetch_assoc()) { ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Transaction Type</label>
                        <select name="transaction_type" class="form-control" required>
                            <option value="Borrow">Borrow</option>
                            <option value="Return">Return</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Transaction</button>
                </div>
            </div>
        </form>
    </div>
</div>
