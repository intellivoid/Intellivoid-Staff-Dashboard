<?PHP
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

?>
<h4 class="border-top mt-5 pt-4 mb-4">Telegram</h4>
<form method="POST" action="<?PHP DynamicalWeb::getRoute('manage_account', array('action'=>'send_notification', 'id'=>$_GET['id']), true); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="mt-1" for="tg-message">Send Telegram Message (HTML Formatting enabled)</label>
                <textarea class="form-control" id="tg-message" name="tg-message" rows="10">Hello User</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3">
                    <input type="submit" class="btn btn-sm btn-outline-primary" value="Send Notification">
                </div>
            </div>
        </div>
    </div>
</form>
