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
use IntellivoidAPI\Abstracts\SearchMethods\RequestRecordSearchMethod;
use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
use IntellivoidAPI\Exceptions\RequestRecordNotFoundException;
use IntellivoidAPI\IntellivoidAPI;

    Runtime::import('IntellivoidAPI');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('request_records'));
    }

    $IntellivoidAPI = new IntellivoidAPI();

    try
    {
        $RequestRecord = $IntellivoidAPI->getRequestRecordManager()->getRequestRecord(
                RequestRecordSearchMethod::byId, $_GET['id']
        );
    }
    catch (RequestRecordNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('request_records', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('request_records', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Request Record (<?PHP HTML::print($RequestRecord->ID); ?>)</title>
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
                                                        <td><?PHP HTML::print(gettype($RequestRecord->ID)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->ID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Reference ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->ReferenceID)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->ReferenceID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Access Record ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->AccessRecordID)); ?></td>
                                                        <td>
                                                            <a href="#">
                                                                <?PHP HTML::print($RequestRecord->AccessRecordID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Application ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->ApplicationID)); ?></td>
                                                        <td>
                                                            <a href="#">
                                                                <?PHP HTML::print($RequestRecord->ApplicationID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Request Path"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->Path)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->Path); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("API Version"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->Version)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->Version); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Response Code"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->ResponseCode)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->ResponseCode); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Response Content Type"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->ResponseContentType)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->ResponseContentType); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Response Length"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->ResponseLength)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->ResponseLength); ?> bytes</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Response Time"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->ResponseTime)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->ResponseTime); ?>ms</td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Request Method"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->RequestMethod)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->RequestMethod); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Request Payload"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->RequestPayload)); ?></td>
                                                        <td><?PHP HTML::print(json_encode($RequestRecord->RequestPayload, JSON_PRETTY_PRINT)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("IP Address"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->IPAddress)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->IPAddress); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("User Agent"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->UserAgent)); ?></td>
                                                        <td><?PHP HTML::print(base64_decode($RequestRecord->UserAgent)); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("::INFO_SCALE Day"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->Day)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->Day); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("::INFO_SCALE Month"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->Month)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->Month); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("::INFO_SCALE Year"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->Year)); ?></td>
                                                        <td><?PHP HTML::print($RequestRecord->Year); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($RequestRecord->Timestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($RequestRecord->Timestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $RequestRecord->Timestamp)); ?>)
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