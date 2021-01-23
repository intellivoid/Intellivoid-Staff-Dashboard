<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\AuthenticationRequestSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod;
use IntellivoidAccounts\Exceptions\AuthenticationAccessNotFoundException;
use IntellivoidAccounts\Exceptions\AuthenticationRequestNotFoundException;
use IntellivoidAccounts\Exceptions\TelegramClientNotFoundException;
use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('tpa/telegram_clients'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $TelegramClient = $IntellivoidAccounts->getTelegramClientManager()->getClient(
            TelegramClientSearchMethod::byId, $_GET['id']
        );
    }
    catch (TelegramClientNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('tpa/telegram_clients', array('callback' => '104')));
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('tpa/telegram_clients', array('callback' => '105')));
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - View Telegram Client (<?PHP HTML::print($TelegramClient->ID); ?>)</title>
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
                                        <h4 class="card-title">Telegram Client Details</h4>
                                        <ul class="nav nav-tabs tab-solid tab-solid-primary" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active show" id="client_details_tab" data-toggle="tab" href="#client_details" role="tab" aria-controls="client_details" aria-selected="true">Client Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="chat_details_tab" data-toggle="tab" href="#chat_details" role="tab" aria-controls="chat_details" aria-selected="false">Chat Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="user_details_tab" data-toggle="tab" href="#user_details" role="tab" aria-controls="user_details" aria-selected="false">User Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="session_data_tab" data-toggle="tab" href="#session_data" role="tab" aria-controls="session_data" aria-selected="false">Session Data</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content tab-content-solid">
                                            <div class="tab-pane fade active show" id="client_details" role="tabpanel" aria-labelledby="client_details_tab">
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
                                                                <td><?PHP HTML::print(gettype($TelegramClient->ID)); ?></td>
                                                                <td><?PHP HTML::print($TelegramClient->ID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Public ID"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->PublicID)); ?></td>
                                                                <td><?PHP HTML::print($TelegramClient->PublicID); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Available"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Available)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print($TelegramClient->Available); ?>
                                                                    <?PHP
                                                                        if($TelegramClient->Available == 1)
                                                                        {
                                                                            HTML::print(" (Available)");
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print(" (Not Available)");
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Account ID"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->AccountID)); ?></td>
                                                                <td>
                                                                    <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('id' => $TelegramClient->AccountID), true); ?>">
                                                                        <?PHP HTML::print($TelegramClient->AccountID); ?>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Last Activity Timestamp"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->LastActivityTimestamp)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($TelegramClient->LastActivityTimestamp)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $TelegramClient->LastActivityTimestamp)); ?>)
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Created Timestamp"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->CreatedTimestamp)); ?></td>
                                                                <td>
                                                                    <?PHP HTML::print(json_encode($TelegramClient->CreatedTimestamp)); ?>
                                                                    (<?PHP HTML::print(date("F j, Y, g:i a", $TelegramClient->CreatedTimestamp)); ?>)
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="chat_details" role="tabpanel" aria-labelledby="chat_details_tab">
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
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Chat->ID)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                        if($TelegramClient->Chat->ID !== null)
                                                                        {
                                                                            HTML::print($TelegramClient->Chat->ID);
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("NULL");
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Type"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Chat->Type)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                        if($TelegramClient->Chat->Type !== null)
                                                                        {
                                                                            HTML::print($TelegramClient->Chat->Type);
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("NULL");
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Title"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Chat->Title)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                        if($TelegramClient->Chat->Title !== null)
                                                                        {
                                                                            HTML::print($TelegramClient->Chat->Title);
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("NULL");
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Username"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Chat->Username)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                        if($TelegramClient->Chat->Username !== null)
                                                                        {
                                                                            HTML::print("<a href=\"", false);
                                                                            HTML::print("https://t.me/" . $TelegramClient->Chat->Username);
                                                                            HTML::print("\" target=\"_blank\">", false);
                                                                            HTML::print($TelegramClient->Chat->Username);
                                                                            HTML::print("</a>", false);
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("NULL");
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("First Name"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Chat->FirstName)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($TelegramClient->Chat->FirstName !== null)
                                                                    {
                                                                        HTML::print($TelegramClient->Chat->FirstName);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Last Name"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Chat->LastName)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                        if($TelegramClient->Chat->LastName !== null)
                                                                        {
                                                                            HTML::print($TelegramClient->Chat->LastName);
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
                                            <div class="tab-pane fade" id="user_details" role="tabpanel" aria-labelledby="user_details_tab">
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
                                                                <td><?PHP HTML::print(gettype($TelegramClient->Chat->ID)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($TelegramClient->User->ID !== null)
                                                                    {
                                                                        HTML::print($TelegramClient->User->ID);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("IsBot"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->User->IsBot)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($TelegramClient->User->IsBot !== null)
                                                                    {
                                                                        if($TelegramClient->User->IsBot)
                                                                        {
                                                                            HTML::print("true");
                                                                        }
                                                                        else
                                                                        {
                                                                            HTML::print("false");
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("First Name"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->User->FirstName)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($TelegramClient->User->FirstName !== null)
                                                                    {
                                                                        HTML::print($TelegramClient->User->FirstName);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Last Name"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->User->LastName)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($TelegramClient->User->LastName !== null)
                                                                    {
                                                                        HTML::print($TelegramClient->User->LastName);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Username"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->User->Username)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($TelegramClient->User->Username !== null)
                                                                    {
                                                                        HTML::print("<a href=\"", false);
                                                                        HTML::print("https://t.me/" . $TelegramClient->User->Username);
                                                                        HTML::print("\" target=\"_blank\">", false);
                                                                        HTML::print($TelegramClient->User->Username);
                                                                        HTML::print("</a>", false);
                                                                    }
                                                                    else
                                                                    {
                                                                        HTML::print("NULL");
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?PHP HTML::print("Language Code"); ?></td>
                                                                <td><?PHP HTML::print(gettype($TelegramClient->User->LanguageCode)); ?></td>
                                                                <td>
                                                                    <?PHP
                                                                    if($TelegramClient->User->LanguageCode !== null)
                                                                    {
                                                                        HTML::print($TelegramClient->User->LanguageCode);
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
                                            <div class="tab-pane fade" id="session_data" role="tabpanel" aria-labelledby="session_data_tab">
                                                <div class="form-group">
                                                    <label for="session_data">Session Data</label>
                                                    <textarea class="form-control" id="session_data" rows="25" readonly><?PHP HTML::print(json_encode($TelegramClient->SessionData->toArray(), JSON_PRETTY_PRINT)); ?></textarea>
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