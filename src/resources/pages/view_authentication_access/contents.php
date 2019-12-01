<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationRequestSearchMethod;
use IntellivoidAccounts\Exceptions\AuthenticationAccessNotFoundException;
use IntellivoidAccounts\Exceptions\AuthenticationRequestNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('authentication_requests'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $AuthenticationAccess = $IntellivoidAccounts->getCrossOverAuthenticationManager()->getAuthenticationAccessManager()->getAuthenticationAccess(
            AuthenticationRequestSearchMethod::byId, $_GET['id']
        );
    }
    catch (AuthenticationAccessNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('authentication_access', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('authentication_access', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Authentication Access (<?PHP HTML::print($AuthenticationAccess->ID); ?>)</title>
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
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->ID)); ?></td>
                                                        <td><?PHP HTML::print($AuthenticationAccess->ID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Access Token"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->AccessToken)); ?></td>
                                                        <td><?PHP HTML::print($AuthenticationAccess->AccessToken); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Application ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->ApplicationId)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $AuthenticationAccess->ApplicationId), true); ?>">
                                                                <?PHP HTML::print($AuthenticationAccess->ApplicationId); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Status"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->Status)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print($AuthenticationAccess->Status); ?>
                                                            <?PHP
                                                                switch($AuthenticationAccess->Status)
                                                                {
                                                                    case AuthenticationAccessStatus::Active:
                                                                        HTML::print(" (Active)");
                                                                        break;

                                                                    case AuthenticationAccessStatus::Revoked:
                                                                        HTML::print(" (Revoked)");
                                                                        break;

                                                                    default:
                                                                        HTML::print(" (Unknown)");
                                                                        break;
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Account ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->AccountId)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $AuthenticationAccess->AccountId), true); ?>">
                                                                <?PHP HTML::print($AuthenticationAccess->AccountId); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("COA Request ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->RequestId)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('view_authentication_request', array('id' => $AuthenticationAccess->RequestId), true); ?>">
                                                                <?PHP HTML::print($AuthenticationAccess->RequestId); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Permissions"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->Permissions)); ?></td>
                                                        <td><?PHP HTML::print(json_encode($AuthenticationAccess->Permissions)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->CreatedTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AuthenticationAccess->CreatedTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AuthenticationAccess->CreatedTimestamp)); ?>)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Expires Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->ExpiresTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AuthenticationAccess->ExpiresTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AuthenticationAccess->ExpiresTimestamp)); ?>)
                                                            <?PHP
                                                                if((int)time() > $AuthenticationAccess->ExpiresTimestamp)
                                                                {
                                                                    HTML::print(" (Expired)");
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Last Used Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationAccess->LastUsedTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AuthenticationAccess->LastUsedTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AuthenticationAccess->LastUsedTimestamp)); ?>)
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