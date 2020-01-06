<?PHP

use DynamicalWeb\DynamicalWeb;
use DynamicalWeb\HTML;
use DynamicalWeb\Javascript;

    $HotlinkAccountID = "";

    if(isset($_GET['account_id']))
    {
        $HotlinkAccountID = 'value="' . htmlspecialchars($_GET['account_id'], ENT_QUOTES, 'UTF-8') . '"';
    }

    HTML::importScript('process_transaction');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Create Transaction</title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="col-lg-12 grid-margin">
                            <?PHP HTML::importScript('callbacks'); ?>
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" action="<?PHP DynamicalWeb::getRoute('transfer_funds', array('action' => 'process_transaction'), true); ?>">
                                        <p class="card-description">Create Transaction</p>
                                        <div class="form-group row">
                                            <label for="sending_account_id" class="col-sm-2 col-form-label">Sending Account ID</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="sending_account_id" name="sending_account_id" placeholder="0" <?PHP HTML::print($HotlinkAccountID, false); ?> required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="receiving_account_id" class="col-sm-2 col-form-label">Receiving Account ID</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="receiving_account_id" name="receiving_account_id" placeholder="0" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="amount" name="amount" placeholder="30.99" required>
                                            </div>
                                        </div>
                                        <input type="submit" class="btn btn-success mr-2" value="Process Transaction">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
    </body>
</html>