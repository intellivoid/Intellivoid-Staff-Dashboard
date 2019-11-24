<?PHP

use DynamicalWeb\DynamicalWeb;
use DynamicalWeb\HTML;
    use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAccounts\Objects\Account;

    function render_details(IntellivoidAccounts $IntellivoidAccounts, Account $account)
    {
        ?>
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label mt-1" for="id">ID</label>
                <div class="col-sm-9">
                    <input type="text" name="id" id="id" class="form-control" placeholder="None" value="<?PHP HTML::print($account->ID, false); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label mt-1" for="public_id">PublicID</label>
                <div class="col-sm-9">
                    <input type="text" name="public_id" id="public_id" class="form-control" placeholder="None" value="<?PHP HTML::print($account->PublicID, false); ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label mt-1" for="configuration_details">Configuration Details</label>
                <div class="col-sm-9">
                    <textarea class="form-control" id="configuration_details" rows="15" readonly><?PHP HTML::print(json_encode($account->Configuration->toArray(), JSON_PRETTY_PRINT)); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label mt-1" for="personal_information_details">Personal Information Details</label>
                <div class="col-sm-9">
                    <textarea class="form-control" id="personal_information_details" rows="15" readonly><?PHP HTML::print(json_encode($account->PersonalInformation->toArray(), JSON_PRETTY_PRINT)); ?></textarea>
                </div>
            </div>
            <div class="form-group row">
                <button type="button" class="btn btn-outline-primary" onclick="location.href='<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $_GET['id'], 'action' => 'export_data'), true); ?>';">Export Data</button>
            </div>
        </div>
        <?PHP
    }