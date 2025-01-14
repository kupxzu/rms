<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="edit_category.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: red " >&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_category_id" name="id">
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" id="edit_category_name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
