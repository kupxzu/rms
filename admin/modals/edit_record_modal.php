<div class="modal fade" id="editRecordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="edit_record.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: red;">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_record_id" name="id">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" id="edit_title" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="edit_description" name="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select id="edit_category" name="category_id" class="form-control">
                            <option value="">Uncategorized</option>
                            <?php 
                            $category_query = $conn->query("SELECT * FROM categories");
                            while ($cat = $category_query->fetch_assoc()) { 
                            ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                            <?php } ?>
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
