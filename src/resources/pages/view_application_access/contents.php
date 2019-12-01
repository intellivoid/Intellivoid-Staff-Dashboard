<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\ApplicationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationAccessSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationRequestSearchMethod;
use IntellivoidAccounts\Exceptions\ApplicationAccessNotFoundException;
use IntellivoidAccounts\Exceptions\AuthenticationRequestNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('application_access'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $ApplicationAccess = $IntellivoidAccounts->getCrossOverAuthenticationManager()->getApplicationAccessManager()->getApplicationAccess(
            ApplicationAccessSearchMethod::byId, $_GET['id']
        );
    }
    catch (ApplicationAccessNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('application_access', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('application_access', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Manage Application Access (<?PHP HTML::print($ApplicationAccess->ID); ?>)</title>
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
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->ID)); ?></td>
                                                        <td><?PHP HTML::print($ApplicationAccess->ID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Public ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->PublicID)); ?></td>
                                                        <td><?PHP HTML::print($ApplicationAccess->PublicID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Application ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->ApplicationID)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $ApplicationAccess->ApplicationID), true); ?>">
                                                                <?PHP HTML::print($ApplicationAccess->ApplicationID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Account ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->AccountID)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $ApplicationAccess->AccountID), true); ?>">
                                                                <?PHP HTML::print($ApplicationAccess->AccountID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Status"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->Status)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print($ApplicationAccess->Status); ?>
                                                            <?PHP
                                                                switch($ApplicationAccess->Status)
                                                                {
                                                                    case ApplicationAccessStatus::Authorized:
                                                                        HTML::print(" (Authorized)");
                                                                        break;

                                                                    case ApplicationAccessStatus::Unauthorized:
                                                                        HTML::print(" (Unauthorized)");
                                                                        break;

                                                                    default:
                                                                        HTML::print(" (Unknown)");
                                                                        break;
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <td><?PHP HTML::print("Requested Permissions"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->Permissions)); ?></td>
                                                        <td><?PHP HTML::print(json_encode($ApplicationAccess->Permissions)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->CreationTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($ApplicationAccess->CreationTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $ApplicationAccess->CreationTimestamp)); ?>)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Last Authenticated Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ApplicationAccess->LastAuthenticatedTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($ApplicationAccess->LastAuthenticatedTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $ApplicationAccess->LastAuthenticatedTimestamp)); ?>)
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