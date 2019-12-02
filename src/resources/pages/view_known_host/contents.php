<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationRequestSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod;
use IntellivoidAccounts\Exceptions\AuthenticationAccessNotFoundException;
use IntellivoidAccounts\Exceptions\AuthenticationRequestNotFoundException;
use IntellivoidAccounts\Exceptions\HostNotKnownException;
use IntellivoidAccounts\Exceptions\TelegramClientNotFoundException;
use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('known_hosts'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $KnownHost = $IntellivoidAccounts->getKnownHostsManager()->getHost(
            KnownHostsSearchMethod::byId, $_GET['id']
        );
    }
    catch (HostNotKnownException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('known_hosts', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('known_hosts', array('callback' => '105')));
    }

    HTML::importScript('block_host');

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'block_host')
        {
            block_host($KnownHost, $IntellivoidAccounts);
        }

        if($_GET['action'] == 'unblock_host')
        {
            unblock_host($KnownHost, $IntellivoidAccounts);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Known Host (<?PHP HTML::print($KnownHost->IpAddress); ?>)</title>
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
                                        <h4 class="card-title">Known Host Details</h4>
                                        <ul class="nav nav-tabs tab-solid tab-solid-primary" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show" id="host_details_tab" data-toggle="tab" href="#host_details" role="tab" aria-controls="host_details" aria-selected="true">Host Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="location_details_tab" data-toggle="tab" href="#location_details" role="tab" aria-controls="location_details" aria-selected="false">Location Details</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content tab-content-solid">
                                            <div class="tab-pane fade active show" id="host_details" role="tabpanel" aria-labelledby="host_details_tab">
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
                                                                <td><?PHP HTML::print(gettype($KnownHost->ID)); ?></td>
                                                                <td><?PHP HTML::print($KnownHost->ID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Public ID"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->PublicID)); ?></td>
                                                                <td><?PHP HTML::print($KnownHost->PublicID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("IP Address"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->IpAddress)); ?></td>
                                                                <td><?PHP HTML::print($KnownHost->IpAddress); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Blocked"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->Blocked)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print($KnownHost->Blocked); ?>
                                                                    <?PHP
                                                                        if($KnownHost->Blocked == 1)
                                                                        {
                                                                            HTML::print(" (Blocked)");
                                                                            ?>
                                                                            <a class="ml-2" href="<?PHP DynamicalWeb::getRoute('view_known_host', array('id' => $_GET['id'], 'action' => 'unblock_host'), true); ?>"> Unblock</a>
                                                                            <?PHP
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print(" (Not Blocked)");
                                                                            ?>
                                                                            <a class="ml-2" href="<?PHP DynamicalWeb::getRoute('view_known_host', array('id' => $_GET['id'], 'action' => 'block_host'), true); ?>"> Block</a>
                                                                            <?PHP
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Last Used"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LastUsed)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($KnownHost->LastUsed)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $KnownHost->LastUsed)); ?>)
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->Created)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($KnownHost->Created)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $KnownHost->Created)); ?>)
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="location_details" role="tabpanel" aria-labelledby="location_details_tab">
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
                                                                <td><?PHP HTML::print("Country Name"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->CountryName)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                        if($KnownHost->LocationData->CountryName !== null)
                                                                        {
                                                                            HTML::print($KnownHost->LocationData->CountryName);
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("NULL");
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Country Code"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->CountryCode)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($KnownHost->LocationData->CountryCode !== null)
                                                                    {
                                                                        HTML::print($KnownHost->LocationData->CountryCode);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Continent Name"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->ContinentName)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($KnownHost->LocationData->ContinentName !== null)
                                                                    {
                                                                        HTML::print($KnownHost->LocationData->ContinentName);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Continent Code"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->ContinentCode)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($KnownHost->LocationData->ContinentCode !== null)
                                                                    {
                                                                        HTML::print($KnownHost->LocationData->ContinentCode);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("City"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->City)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($KnownHost->LocationData->City !== null)
                                                                    {
                                                                        HTML::print($KnownHost->LocationData->City);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Zip Code"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->ZipCode)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($KnownHost->LocationData->ZipCode !== null)
                                                                    {
                                                                        HTML::print($KnownHost->LocationData->ZipCode);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Longitude"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->Longitude)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($KnownHost->LocationData->Longitude !== null)
                                                                    {
                                                                        HTML::print($KnownHost->LocationData->Longitude);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Latitude"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->Latitude)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($KnownHost->LocationData->Latitude !== null)
                                                                    {
                                                                        HTML::print($KnownHost->LocationData->Latitude);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Last Updated"); ?></td>
                                                                <td><?PHP HTML::print(gettype($KnownHost->LocationData->LastUpdated)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                        if($KnownHost->LocationData->LastUpdated !== null)
                                                                        {
                                                                            HTML::print(json_encode($KnownHost->LocationData->LastUpdated));
                                                                            HTML::print(date(" (F j, Y, g:i a)", $KnownHost->LocationData->LastUpdated));
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("NULL");
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
                        </div>

                    </div>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
    </body>
</html>