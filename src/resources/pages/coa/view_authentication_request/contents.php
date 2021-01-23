<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationRequestSearchMethod;
    use IntellivoidAccounts\Exceptions\AuthenticationRequestNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('coa/authentication_requests'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $AuthenticationRequest = $IntellivoidAccounts->getCrossOverAuthenticationManager()->getAuthenticationRequestManager()->getAuthenticationRequest(
            AuthenticationRequestSearchMethod::byId, $_GET['id']
        );
    }
    catch (AuthenticationRequestNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('coa/authentication_requests', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('coa/authentication_requests', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Authentication Request (<?PHP HTML::print($AuthenticationRequest->Id); ?>)</title>
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
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->Id)); ?></td>
                                                        <td><?PHP HTML::print($AuthenticationRequest->Id); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Request Token"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->RequestToken)); ?></td>
                                                        <td><?PHP HTML::print($AuthenticationRequest->RequestToken); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Application ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->ApplicationId)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $AuthenticationRequest->ApplicationId), true); ?>">
                                                                <?PHP HTML::print($AuthenticationRequest->ApplicationId); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Status"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->Status)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print($AuthenticationRequest->Status); ?>
                                                            <?PHP
                                                                switch($AuthenticationRequest->Status)
                                                                {
                                                                    case AuthenticationRequestStatus::Active:
                                                                        HTML::print(" (Active)");
                                                                        break;

                                                                    case AuthenticationRequestStatus::Blocked:
                                                                        HTML::print(" (Blocked)");
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
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->AccountId)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('id' => $AuthenticationRequest->AccountId), true); ?>">
                                                                <?PHP HTML::print($AuthenticationRequest->AccountId); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Host ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->HostId)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $AuthenticationRequest->HostId), true); ?>">
                                                                <?PHP HTML::print($AuthenticationRequest->HostId); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Requested Permissions"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->RequestedPermissions)); ?></td>
                                                        <td><?PHP HTML::print(json_encode($AuthenticationRequest->RequestedPermissions)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->CreatedTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AuthenticationRequest->CreatedTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AuthenticationRequest->CreatedTimestamp)); ?>)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Expires Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($AuthenticationRequest->ExpiresTimestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($AuthenticationRequest->ExpiresTimestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $AuthenticationRequest->ExpiresTimestamp)); ?>)
                                                            <?PHP
                                                                if((int)time() > $AuthenticationRequest->ExpiresTimestamp)
                                                                {
                                                                    HTML::print(" (Expired)");
                                                                }
                                                            ?>
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