<?PHP
    use DynamicalWeb\DynamicalWeb;
?>
<div class="modal fade" id="searchDialog" tabindex="-1" role="dialog" aria-labelledby="searchDialogLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?PHP DynamicalWeb::getRoute('api/request_records', array('action' => 'search'), true); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchDialog">API Request Records Search</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <i class="mdi mdi-close"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="by">By</label>
                        <select name="by" id="by" class="form-control">
                            <option value="id">ID</option>
                            <option value="reference_id">Reference ID</option>
                        </select>
                    </div>
                    <div class="from-group">
                        <label for="value">Value</label>
                        <input class="form-control" type="text" name="value" id="value" placeholder="Search Values">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Search">
                </div>
            </form>

        </div>
    </div>
</div>