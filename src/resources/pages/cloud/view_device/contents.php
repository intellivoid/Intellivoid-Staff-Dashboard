<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationRequestSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\TrackingUserAgentSearchMethod;
use IntellivoidAccounts\Exceptions\AuthenticationAccessNotFoundException;
use IntellivoidAccounts\Exceptions\AuthenticationRequestNotFoundException;
use IntellivoidAccounts\Exceptions\TelegramClientNotFoundException;
use IntellivoidAccounts\Exceptions\UserAgentNotFoundException;
use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/devices'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $Device = $IntellivoidAccounts->getTrackingUserAgentManager()->getRecord(
            TrackingUserAgentSearchMethod::byId, $_GET['id']
        );
    }
    catch(UserAgentNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/devices', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/devices', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Device (<?PHP HTML::print($Device->ID); ?>)</title>
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
                                                        <td><?PHP HTML::print(gettype($Device->ID)); ?></td>
                                                        <td><?PHP HTML::print($Device->ID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Tracking ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->TrackingID)); ?></td>
                                                        <td><?PHP HTML::print($Device->TrackingID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("User Agent"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->UserAgentString)); ?></td>
                                                        <td><?PHP HTML::print($Device->UserAgentString); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Platform"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->Platform)); ?></td>
                                                        <td><?PHP HTML::print($Device->Platform); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Browser"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->Browser)); ?></td>
                                                        <td><?PHP HTML::print($Device->Browser); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Version"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->Version)); ?></td>
                                                        <td><?PHP HTML::print($Device->Version); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Host ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->HostID)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $Device->HostID), true); ?>">
                                                                <?PHP HTML::print($Device->HostID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->Created)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($Device->Created)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $Device->Created)); ?>)
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Last Seen"); ?></td>
                                                        <td><?PHP HTML::print(gettype($Device->LastSeen)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($Device->LastSeen)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $Device->LastSeen)); ?>)
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