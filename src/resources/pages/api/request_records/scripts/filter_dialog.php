<?PHP
    use DynamicalWeb\DynamicalWeb;
?>
<div class="modal fade" id="filterDialog" tabindex="-1" role="dialog" aria-labelledby="filterDialogLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="GET" action="<?PHP DynamicalWeb::getRoute('api/request_records', array(), true); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterDialog">Filter Request Records</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <i class="mdi mdi-close"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="filter">By</label>
                        <select name="filter" id="filter" class="form-control">
                            <option value="request_method">Request Method</option>
                            <option value="version">Version</option>
                            <option value="path">Path</option>
                            <option value="ip_address">IP Address</option>
                            <option value="response_code">Response Code</option>
                            <option value="access_record_id">Access Record ID</option>
                            <option value="application_id">Application ID</option>
                        </select>
                    </div>
                    <div class="from-group">
                        <label for="value">Filter Value</label>
                        <input class="form-control" type="text" name="value" id="value" placeholder="Filter Value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Filter Results">
                </div>
            </form>

        </div>
    </div>
</div>