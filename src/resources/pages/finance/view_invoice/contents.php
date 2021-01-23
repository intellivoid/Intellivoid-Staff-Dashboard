<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\TransactionLogSearchMethod;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\TransactionRecordNotFoundException;

    Runtime::import('IntellivoidAccounts');

    $IntellivoidAccounts = new IntellivoidAccounts\IntellivoidAccounts();

    try
    {
        $TransactionRecord = $IntellivoidAccounts->getTransactionRecordManager()->getTransactionRecord(
            TransactionLogSearchMethod::byId, $_GET['id']
        );
    }
    catch(TransactionRecordNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
            'finance/transaction_records', array('callback' => '104')
        ));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
                'finance/transaction_records', array('callback' => '105')
        ));
    }

    try
    {
        $Account = $IntellivoidAccounts->getAccountManager()->getAccount(
            AccountSearchMethod::byId, $TransactionRecord->AccountID
        );
    }
    catch (AccountNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
            'finance/transaction_records', array('callback' => '106')
        ));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
            'finance/transaction_records', array('callback' => '105')
        ));
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Invoice&nbsp;&nbsp;#IV-<?PHP HTML::print($TransactionRecord->ID); ?></title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card px-2">
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <h3 class="text-right my-5">Invoice&nbsp;&nbsp;#IV-<?PHP HTML::print($TransactionRecord->ID); ?></h3>
                                            <hr> </div>
                                        <div class="container-fluid d-flex justify-content-between">
                                            <div class="col-lg-4 pl-0">
                                                <?PHP
                                                    $FromAccount = null;

                                                    try
                                                    {
                                                        $FromAccount = $IntellivoidAccounts->getAccountManager()->getAccount(
                                                            AccountSearchMethod::byUsername, $TransactionRecord->Vendor
                                                        );
                                                    }
                                                    catch(Exception $e)
                                                    {
                                                        $FromAccount = null;
                                                    }
                                                ?>
                                                <p class="mt-5 mb-2">
                                                    <b><?PHP HTML::print($TransactionRecord->Vendor); ?></b>
                                                </p>
                                                <?PHP
                                                    if($FromAccount !== null)
                                                    {
                                                        ?>
                                                        <p><?PHP HTML::print($FromAccount->Email); ?></p>
                                                        <p>Vendor ID:
                                                            <code><?PHP HTML::print($FromAccount->PublicID); ?></code>
                                                        </p>
                                                        <?PHP
                                                    }
                                                ?>
                                            </div>
                                            <div class="col-lg-4 pr-0">
                                                <p class="mt-5 mb-2 text-right">
                                                    <b>Invoice to</b>
                                                </p>
                                                <p class="text-right">
                                                    @<?PHP HTML::print($Account->Username); ?>
                                                    <br> <?PHP HTML::print($Account->Email); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="container-fluid d-flex justify-content-between">
                                            <div class="col-lg-3 pl-0">
                                                <p class="mb-0 mt-5">Invoice Date : <?PHP HTML::print(date("F j, Y, g:i a", $TransactionRecord->Timestamp)); ?></p>
                                            </div>
                                        </div>
                                        <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                                            <div class="table-responsive w-100">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="bg-dark text-white">
                                                            <th>#</th>
                                                            <th>Description</th>
                                                            <th class="text-right">Quantity</th>
                                                            <th class="text-right">Unit cost</th>
                                                            <th class="text-right">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="text-right">
                                                            <td class="text-left">1</td>
                                                            <td class="text-left">Transaction</td>
                                                            <td>1</td>
                                                            <td>$<?PHP HTML::print($TransactionRecord->Amount); ?> USD</td>
                                                            <td>$<?PHP HTML::print($TransactionRecord->Amount); ?> USD</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="container-fluid mt-5 w-100">
                                            <h4 class="text-right mb-5">Total $<?PHP HTML::print($TransactionRecord->Amount); ?> USD</h4>
                                        </div>
                                    </div>
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