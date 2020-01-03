<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationRequestSearchMethod;
    use IntellivoidAccounts\Exceptions\AuthenticationRequestNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAPI\Abstracts\AccessRecordStatus;
use IntellivoidAPI\Abstracts\SearchMethods\AccessRecordSearchMethod;
use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
use IntellivoidAPI\IntellivoidAPI;

    Runtime::import('IntellivoidAPI');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('access_records'));
    }

    $IntellivoidAPI = new IntellivoidAPI();

    try
    {
        $AccessRecord = $IntellivoidAPI->getAccessKeyManager()->getAccessRecord(
                AccessRecordSearchMethod::byId, $_GET['id']
        );

    }
    catch (AccessRecordNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('access_records', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('access_records', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Access Record (<?PHP HTML::print($AccessRecord->ID); ?>)</title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Type</th>
                                                    <th>Value</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?PHP HTML::print("ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->ID)); ?></td>
                                                        <td><?PHP HTML::print($AccessRecord->ID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Subscription ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->SubscriptionID)); ?></td>
                                                        <td>
                                                            <a href="#">
                                                                <?PHP HTML::print($AccessRecord->SubscriptionID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Application ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->ApplicationID)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $AccessRecord->ApplicationID), true); ?>">
                                                                <?PHP HTML::print($AccessRecord->ApplicationID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Access Key"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->AccessKey)); ?></td>
                                                        <td><?PHP HTML::print($AccessRecord->AccessKey); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Last Changed Access Key"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->LastChangedAccessKey)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AccessRecord->LastChangedAccessKey)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AccessRecord->LastChangedAccessKey)); ?>)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Status"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->Status)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print($AccessRecord->Status); ?>
                                                            <?PHP
                                                            switch($AccessRecord->Status)
                                                            {
                                                                case AccessRecordStatus::Available:
                                                                    HTML::print(" (Available)");
                                                                    break;

                                                                case AccessRecordStatus::Disabled:
                                                                    HTML::print(" (Disabled)");
                                                                    break;

                                                                case AccessRecordStatus::BillingError:
                                                                    HTML::print(" (Billing Error)");
                                                                    break;

                                                                default:
                                                                    HTML::print(" (Unknown)");
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Rate Limit Type"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->RateLimitType)); ?></td>
                                                        <td><?PHP HTML::print($AccessRecord->RateLimitType); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Rate Limit Configuration"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->RateLimitConfiguration)); ?></td>
                                                        <td><?PHP HTML::print(json_encode($AccessRecord->RateLimitConfiguration, JSON_PRETTY_PRINT)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Variables"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->Variables)); ?></td>
                                                        <td><?PHP HTML::print(json_encode($AccessRecord->Variables, JSON_PRETTY_PRINT)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Last Activity"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->LastActivity)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AccessRecord->LastActivity)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AccessRecord->LastActivity)); ?>)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Created"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AccessRecord->CreatedTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AccessRecord->CreatedTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AccessRecord->CreatedTimestamp)); ?>)
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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