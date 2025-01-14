<div class="modal fade" id="deleteTransactionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" action="delete_transaction.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the transaction for <strong id="delete_transaction_record"></strong>?</p>
                    <input type="hidden" id="delete_transaction_id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>
