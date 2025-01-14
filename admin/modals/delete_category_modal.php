<div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="delete_category.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: red " >&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="delete_category_name"></strong>?</p>
                    <input type="hidden" id="delete_category_id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
