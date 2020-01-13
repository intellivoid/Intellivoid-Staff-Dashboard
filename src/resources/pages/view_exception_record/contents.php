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
use IntellivoidAPI\Abstracts\SearchMethods\ExceptionRecordSearchMethod;
use IntellivoidAPI\Abstracts\SearchMethods\RequestRecordSearchMethod;
use IntellivoidAPI\Exceptions\AccessRecordNotFoundException;
use IntellivoidAPI\Exceptions\ExceptionRecordNotFoundException;
use IntellivoidAPI\Exceptions\RequestRecordNotFoundException;
use IntellivoidAPI\IntellivoidAPI;

    Runtime::import('IntellivoidAPI');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('exception_records'));
    }

    $IntellivoidAPI = new IntellivoidAPI();

    try
    {
        $ExceptionRecord = $IntellivoidAPI->getExceptionRecordManager()->getExceptionRecord(
                ExceptionRecordSearchMethod::byId, $_GET['id']
        );
    }
    catch (ExceptionRecordNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('exception_records', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('exception_records', array('callback' => '105')));
    }

    DynamicalWeb::setMemoryObject('intellivoid_api', $IntellivoidAPI);
    DynamicalWeb::setMemoryObject('exception_record', $ExceptionRecord);

    HTML::importScript('export_exception_record');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Exception Record (<?PHP HTML::print($ExceptionRecord->ID); ?>)</title>
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
                                    <div class="card-header header-sm d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Exception Record - <?PHP HTML::print($ExceptionRecord->ID); ?></h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" onclick="location.href='<?PHP DynamicalWeb::getRoute('view_exception_record', array('id' => $_GET['id'], 'action' => 'export'), true); ?>'" type="button">
                                                <i class="mdi mdi-export"></i>
                                            </button>
                                        </div>
                                    </div>
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
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->ID)); ?></td>
                                                        <td><?PHP HTML::print($ExceptionRecord->ID); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Request Record ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->RequestRecordID)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('view_request_record', array('id' => $ExceptionRecord->RequestRecordID), true); ?>">
                                                                <?PHP HTML::print($ExceptionRecord->RequestRecordID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Application ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->ApplicationID)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $ExceptionRecord->ApplicationID), true); ?>">
                                                                <?PHP HTML::print($ExceptionRecord->ApplicationID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Access Record ID"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->AccessRecordID)); ?></td>
                                                        <td>
                                                            <a href="<?PHP DynamicalWeb::getRoute('view_access_record', array('id' => $ExceptionRecord->AccessRecordID), true); ?>">
                                                                <?PHP HTML::print($ExceptionRecord->AccessRecordID); ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Message"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->Message)); ?></td>
                                                        <td><?PHP HTML::print($ExceptionRecord->Message); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("File"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->File)); ?></td>
                                                        <td><?PHP HTML::print($ExceptionRecord->File); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Line"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->Line)); ?></td>
                                                        <td><?PHP HTML::print($ExceptionRecord->Line); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Code"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->Code)); ?></td>
                                                        <td><?PHP HTML::print($ExceptionRecord->Code); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?PHP HTML::print("Trace"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->Trace)); ?></td>
                                                        <td>
                                                            <pre class="text-white"><?PHP HTML::print(json_encode($ExceptionRecord->Trace, JSON_PRETTY_PRINT)); ?></pre>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td><?PHP HTML::print("Timestamp"); ?></td>
                                                        <td><?PHP HTML::print(gettype($ExceptionRecord->Timestamp)); ?></td>
                                                        <td>
                                                            <?PHP HTML::print(json_encode($ExceptionRecord->Timestamp)); ?>
                                                            (<?PHP HTML::print(date("F j, Y, g:i a", $ExceptionRecord->Timestamp)); ?>)
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