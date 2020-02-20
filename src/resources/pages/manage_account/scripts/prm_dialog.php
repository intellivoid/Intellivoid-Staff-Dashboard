<?PHP
    use DynamicalWeb\DynamicalWeb;
?>
<div class="modal fade" id="prmDialog" tabindex="-1" role="dialog" aria-labelledby="prmDialogLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prmDialog">Password Recovery Mode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <i class="mdi mdi-close"></i>
                        </span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure you want to enable Password Recovery Mode?
                    This will remove all two-factor authentication protection and apply a temporary password, using
                    this temporary password when logging in will allow the user to set a new password.
                    This temporary password is not the new password
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" value="Enable" onclick="location.href='<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_status', 'status' => 'prm'), true); ?>';">Enable</button>
            </div>
        </div>
    </div>
</div>