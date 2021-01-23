<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use OpenBlu\Abstracts\SearchMethods\VPN;
    use OpenBlu\Exceptions\VPNNotFoundException;
    use OpenBlu\OpenBlu;

    Runtime::import('OpenBlu');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('openblu_servers'));
    }

    $OpenBlu = new OpenBlu();

    try
    {
        $Server = $OpenBlu->getVPNManager()->getVPN(
            VPN::byID, $_GET['id']
        );
    }
    catch (VPNNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('openblu_servers', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('openblu_servers', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - OpenBlu Server (<?PHP HTML::print($Server->IP); ?>)</title>
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
                                        <h4 class="card-title">OpenBlu Server Details</h4>
                                        <ul class="nav nav-tabs tab-solid tab-solid-primary" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show" id="host_details_tab" data-toggle="tab" href="#host_details" role="tab" aria-controls="host_details" aria-selected="true">Server Details</a>
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
                                                                <td><?PHP HTML::print(gettype($Server->ID)); ?></td>
                                                                <td><?PHP HTML::print($Server->ID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Public ID"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->PublicID)); ?></td>
                                                                <td><?PHP HTML::print($Server->PublicID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("IP Address"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->IP)); ?></td>
                                                                <td><?PHP HTML::print($Server->IP); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Score"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->Score)); ?></td>
                                                                <td><?PHP HTML::print($Server->Score); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Ping"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->Ping)); ?></td>
                                                                <td><?PHP HTML::print($Server->Ping); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Country"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->Country)); ?></td>
                                                                <td><?PHP HTML::print($Server->Country); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Country Short"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->CountryShort)); ?></td>
                                                                <td><?PHP HTML::print($Server->CountryShort); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Sessions"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->Sessions)); ?></td>
                                                                <td><?PHP HTML::print($Server->Sessions); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Total Sessions"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->TotalSessions)); ?></td>
                                                                <td><?PHP HTML::print($Server->TotalSessions); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Configuration Parameters"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->ConfigurationParameters)); ?></td>
                                                                <td><pre class="text-white"><?PHP HTML::print(json_encode($Server->ConfigurationParameters, JSON_PRETTY_PRINT)); ?></pre></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Certificate Authority"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->CertificateAuthority)); ?></td>
                                                                <td><pre class="text-white"><?PHP HTML::print($Server->CertificateAuthority); ?></pre></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Certificate"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->Certificate)); ?></td>
                                                                <td><pre class="text-white"><?PHP HTML::print($Server->Certificate); ?></pre></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Private RSA Key"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->Key)); ?></td>
                                                                <td><pre class="text-white"><?PHP HTML::print($Server->Key); ?></pre></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Last Updated"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->LastUpdated)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($Server->LastUpdated)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $Server->LastUpdated)); ?>)
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Server->Created)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($Server->Created)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $Server->Created)); ?>)
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