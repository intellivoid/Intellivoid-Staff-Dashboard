<?PHP
    use DynamicalWeb\DynamicalWeb;
?>
<div class="modal fade" id="gbaDialog" tabindex="-1" role="dialog" aria-labelledby="gbaDialogLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gbaDialog">Government Backed Attack Mode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <i class="mdi mdi-close"></i>
                        </span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to enable Government Backed Attack mode? this will prevent any further authentications.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" value="Enable" onclick="location.href='<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'set_status', 'status' => 'gba_mode'), true); ?>';">Enable</button>
            </div>
        </div>
    </div>
</div>