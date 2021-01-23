<?PHP
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Account;

    function render_telegram_details(IntellivoidAccounts $intellivoidAccounts, Account $account)
    {
        $TelegramClient = $intellivoidAccounts->getTelegramClientManager()->getClient(
                TelegramClientSearchMethod::byId, $account->Configuration->VerificationMethods->TelegramLink->ClientId
        );
        ?>
        <h4 class="border-top mt-5 pt-4 mb-4">
            Telegram Client
            <a href="<?PHP DynamicalWeb::getRoute('tpa/view_telegram_client', array('id' => $TelegramClient->ID), true); ?>">
                <i class="mdi mdi-database-search pl-2"></i>
            </a>
        </h4>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label mt-1" for="tg-id">Client ID</label>
            <div class="col-sm-9">
                <input type="text" name="tg-id" id="tg-id" class="form-control" placeholder="None" value="<?PHP HTML::print($TelegramClient->ID, false); ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label mt-1" for="tg-pubid">Client Public ID</label>
            <div class="col-sm-9">
                <input type="text" name="tg-pubid" id="tg-pubid" class="form-control" placeholder="None" value="<?PHP HTML::print($TelegramClient->PublicID, false); ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label mt-1" for="tg-last-activity">Last Activity</label>
            <div class="col-sm-9">
                <input type="text" name="tg-last-activity" id="tg-last-activity" class="form-control" placeholder="None" value="<?PHP HTML::print(date("F j, Y, g:i a", $TelegramClient->LastActivityTimestamp)); ?>" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label mt-1" for="tg-userid">User ID</label>
            <div class="col-sm-9">
                <input type="text" name="tg-userid" id="tg-userid" class="form-control" placeholder="None" value="<?PHP HTML::print($TelegramClient->User->ID, false); ?>" readonly>
            </div>
        </div>
        <form method="POST" class="mt-5" action="<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('action'=>'send_notification', 'id'=>$_GET['id']), true); ?>">
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
        <?PHP
    }
