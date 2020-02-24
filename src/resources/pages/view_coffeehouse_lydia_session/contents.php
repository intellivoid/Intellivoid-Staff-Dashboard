<?PHP

    use CoffeeHouse\Abstracts\ForeignSessionSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\ForeignSessionNotFoundException;
    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;

    Runtime::import('CoffeeHouse');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('coffeehouse_lydia_sessions'));
    }

    $CoffeeHouse = new CoffeeHouse();

    try
    {
        $Session = $CoffeeHouse->getForeignSessionsManager()->getSession(
            ForeignSessionSearchMethod::byId, $_GET['id']
        );
    }
    catch (ForeignSessionNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('coffeehouse_lydia_sessions', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('coffeehouse_lydia_sessions', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - CoffeeHouse Lydia Chat Session (<?PHP HTML::print($Session->SessionID); ?>)</title>
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
                                        <h4 class="card-title">Lydia Session Details</h4>
                                        <ul class="nav nav-tabs tab-solid tab-solid-primary" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show" id="session_details_tab" data-toggle="tab" href="#session_details" role="tab" aria-controls="session_details" aria-selected="true">Session Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link show" id="chat_history_tab" data-toggle="tab" href="#chat_history" role="tab" aria-controls="chat_history" aria-selected="true">Chat History</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content tab-content-solid">
                                            <div class="tab-pane fade active show" id="session_details" role="tabpanel" aria-labelledby="session_details_tab">
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
                                                                <td><?PHP HTML::print(gettype($Session->ID)); ?></td>
                                                                <td><?PHP HTML::print($Session->ID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Session ID"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Session->SessionID)); ?></td>
                                                                <td><?PHP HTML::print($Session->SessionID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Total Messages"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Session->Messages)); ?></td>
                                                                <td><?PHP HTML::print($Session->Messages); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Language"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Session->Language)); ?></td>
                                                                <td><?PHP HTML::print($Session->Language); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Last Updated"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Session->LastUpdated)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($Session->LastUpdated)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $Session->LastUpdated)); ?>)
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Expires"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Session->Expires)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($Session->Expires)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $Session->Expires)); ?>)
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                                <td><?PHP HTML::print(gettype($Session->Created)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($Session->Created)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $Session->Created)); ?>)
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="chat_history" role="tabpanel" aria-labelledby="chat_history_tab">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Input</th>
                                                            <th>Output</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                            $SessionID = $CoffeeHouse->getDatabase()->real_escape_string($Session->SessionID);
                                                            $Query = "SELECT id, input, output FROM `chat_dialogs` WHERE session_id='$SessionID' ORDER BY `step` ASC;";

                                                            $QueryResults = $CoffeeHouse->getDatabase()->query($Query);

                                                            if($QueryResults == false)
                                                            {
                                                                throw new Exception($CoffeeHouse->getDatabase()->error);
                                                            }
                                                            else
                                                            {

                                                                while ($Row = $QueryResults->fetch_assoc())
                                                                {
                                                                    $ResultsArray[] = $Row;
                                                                }
                                                            }

                                                            foreach($ResultsArray as $Context)
                                                            {
                                                                ?>
                                                                <tr>
                                                                    <td><?PHP HTML::print($Context['id']); ?></td>
                                                                    <td><?PHP HTML::print(base64_decode($Context['input'])); ?></td>
                                                                    <td><?PHP HTML::print(base64_decode($Context['output'])); ?></td>
                                                                </tr>
                                                                <?PHP
                                                            }
                                                        ?>

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