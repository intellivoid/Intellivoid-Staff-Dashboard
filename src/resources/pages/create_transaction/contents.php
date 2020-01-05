<?PHP
    use DynamicalWeb\HTML;
use DynamicalWeb\Javascript;

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
                                    <form method="POST" action="<?PHP \DynamicalWeb\DynamicalWeb::getRoute('create_transaction', array('action' => 'process_transaction'), true); ?>">
                                        <p class="card-description">Create Transaction</p>
                                        <div class="form-group row">
                                            <label for="account_id" class="col-sm-2 col-form-label">Account ID</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="account_id" name="account_id" placeholder="0" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="vendor" class="col-sm-2 col-form-label">Vendor</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="vendor" name="vendor" placeholder="PayPal" required>
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