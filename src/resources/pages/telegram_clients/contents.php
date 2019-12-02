<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAccounts\Objects\COA\Application;
use IntellivoidAccounts\Objects\TelegramClient\Chat;
use IntellivoidAccounts\Objects\TelegramClient\User;
use msqg\Abstracts\SortBy;
use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    Runtime::import('IntellivoidAccounts');
    HTML::importScript('process_search');
    HTML::importScript('db_render_helper');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $where = null;
    $where_value = null;

    if(isset($_GET['filter']))
    {
        if($_GET['filter'] == 'account_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'account_id';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'chat_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'chat_id';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'user_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'user_id';
                $where_value = (int)$_GET['value'];
            }
        }
    }

    $Results = get_results($IntellivoidAccounts->database, 5000, 'telegram_clients', 'id',
        QueryBuilder::select(
                'telegram_clients', ['id', 'public_id', 'available', 'account_id', 'chat', 'user', 'user_id', 'chat_id', 'last_activity'],
                $where, $where_value, 'last_activity', SortBy::descending
        ),
    $where, $where_value);

    function render_app_dropdown(IntellivoidAccounts $intellivoidAccounts, int $id)
    {
        try
        {
            $ApplicationStatus = DynamicalWeb::getBoolean("APP_$id");
        }
        catch (Exception $e)
        {
            try
            {
                $Application = $intellivoidAccounts->getApplicationManager()->getApplication(
                    ApplicationSearchMethod::byId, $id
                );

                DynamicalWeb::setMemoryObject("APP_$id", $Application);
                DynamicalWeb::setBoolean("APP_$id", true);
                $ApplicationStatus = true;

            }
            catch(Exception $exception)
            {
                DynamicalWeb::setBoolean("APP_$id", false);
                $ApplicationStatus = false;
            }
        }

        if($ApplicationStatus == true)
        {
            /** @var Application $Application */
            $Application = DynamicalWeb::getMemoryObject("APP_$id");

            ?>
            <div class="d-flex text-white">
                <img src="<?PHP HTML::print(getApplicationUrl($Application->PublicAppId, 'tiny')); ?>" class="img-fluid rounded-circle" alt="<?PHP HTML::print($Application->Name); ?>">
                <div class="d-flex flex-column ml-2 mr-5">
                    <h6 class="mb-0"><?PHP HTML::print($Application->Name); ?></h6>
                </div>
            </div>
            <div class="border-top mt-3 mb-3"></div>
            <div class="row ml-auto">
                <a href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $id), true) ?>" class="text-white">
                    <i class="mdi mdi-pencil"></i>
                </a>
                <a href="<?PHP DynamicalWeb::getRoute('authentication_access', array('filter' => 'application_id', 'value' => $id), true) ?>" class="text-white pl-2">
                    <i class="mdi mdi-filter"></i>
                </a>
            </div>
            <?PHP
        }
        else
        {
            ?>
            <div class="d-flex text-white">
                <i class="mdi mdi-block-helper text-danger icon-md"></i>
                <div class="d-flex flex-column ml-2 mr-5">
                    <h6 class="mb-0">Application not Found</h6>
                </div>
            </div>
            <div class="border-top mt-3 mb-3"></div>
            <div class="row ml-auto">
                <a href="<?PHP DynamicalWeb::getRoute('authentication_access', array('filter' => 'application_id', 'value' => $id), true) ?>" class="text-white">
                    <i class="mdi mdi-filter"></i>
                </a>
            </div>
            <?PHP
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Telegram Clients</title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <?PHP HTML::importScript('callbacks'); ?>
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-header header-sm d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Telegram Clients</h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" data-toggle="modal" data-target="#filterDialog" type="button">
                                                <i class="mdi mdi-filter"></i>
                                            </button>
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" data-toggle="modal" data-target="#searchDialog" type="button">
                                                <i class="mdi mdi-magnify"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Public ID</th>
                                                        <th>Account ID</th>
                                                        <th>User ID</th>
                                                        <th>Chat ID</th>
                                                        <th>Available</th>
                                                        <th>Last Activity</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?PHP
                                                foreach($Results['results'] as $telegram_client)
                                                {
                                                    $public_id = $telegram_client['public_id'];
                                                    $telegram_client['public_id'] = (strlen($telegram_client['public_id']) > 15) ? substr($telegram_client['public_id'], 0, 15) . '...' : $telegram_client['public_id'];
                                                    $user_object = User::fromArray(ZiProto::decode($telegram_client['user']));
                                                    $chat_object = Chat::fromArray(ZiProto::decode($telegram_client['chat']));
                                                    ?>
                                                    <tr>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($telegram_client['id']); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($public_id); ?>"><?PHP HTML::print($telegram_client['public_id']); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($telegram_client['account_id']); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-account text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <h6 class="mb-0">Account ID <?PHP HTML::print($telegram_client['account_id']); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $telegram_client['account_id']), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-pencil"></i>
                                                                        </a>
                                                                        <a href="<?PHP DynamicalWeb::getRoute('telegram_clients', array('filter' => 'account_id', 'value' => $telegram_client['account_id']), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($telegram_client['user_id']); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-telegram text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <?PHP
                                                                                $DisplayName = "Unknown";

                                                                                if($user_object->Username == null)
                                                                                {
                                                                                    if($user_object->LastName == null)
                                                                                    {
                                                                                        $DisplayName = htmlspecialchars($user_object->FirstName, ENT_QUOTES, 'UTF-8');
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $DisplayName = htmlspecialchars($user_object->FirstName . ' ' . $user_object->LastName, ENT_QUOTES, 'UTF-8');
                                                                                    }
                                                                                }
                                                                                else
                                                                                {
                                                                                    $DisplayName = '@' . $user_object->Username;
                                                                                    $DisplayName = "<a href=\"https://t.me/" . htmlspecialchars($user_object->Username, ENT_QUOTES, 'UTF-8') . "\">" . $DisplayName . "</a>";
                                                                                }

                                                                            ?>
                                                                            <h6 class="mb-0"><?PHP HTML::print($DisplayName, false); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('telegram_clients', array('filter' => 'account_id', 'value' => $telegram_client['account_id']), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($telegram_client['chat_id']); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-telegram text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <?PHP
                                                                                $DisplayName = "Unknown";

                                                                                if($chat_object->Username == null)
                                                                                {
                                                                                    if($chat_object->Title == null)
                                                                                    {
                                                                                        if($chat_object->LastName == null)
                                                                                        {
                                                                                            $DisplayName = htmlspecialchars($chat_object->FirstName, ENT_QUOTES, 'UTF-8');
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            $DisplayName = htmlspecialchars($chat_object->FirstName . ' ' . $chat_object->LastName, ENT_QUOTES, 'UTF-8');
                                                                                        }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $DisplayName = htmlspecialchars($chat_object->Title, ENT_QUOTES, 'UTF-8');
                                                                                    }
                                                                                }
                                                                                else
                                                                                {
                                                                                    $DisplayName = '@' . $chat_object->Username;
                                                                                    $DisplayName = "<a href=\"https://t.me/" . htmlspecialchars($chat_object->Username, ENT_QUOTES, 'UTF-8') . "\">" . $DisplayName . "</a>";
                                                                                }
                                                                            ?>
                                                                            <h6 class="mb-0"><?PHP HTML::print($DisplayName, false); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('telegram_clients', array('filter' => 'account_id', 'value' => $telegram_client['account_id']), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <?PHP
                                                            switch($telegram_client['available'])
                                                            {
                                                                case true:
                                                                    HTML::print("<label class=\"badge badge-success\">Available</label>", false);
                                                                    break;

                                                                case false:
                                                                    HTML::print("<label class=\"badge badge-danger\">Not Available</label>", false);
                                                                    break;

                                                                default:
                                                                    HTML::print("<label class=\"badge badge-outline-primary\">Unknown</label>", false);
                                                            }
                                                            ?>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $telegram_client['last_activity'])); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_telegram_client', array('id' => $telegram_client['id']), true); ?>">View Details</a>
                                                                    <?PHP
                                                                        if($telegram_client['account_id'] > 0)
                                                                        {
                                                                            ?>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $telegram_client['id']), true); ?>">Manage Account</a>
                                                                            <?PHP
                                                                        }
                                                                    ?>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('telegram_clients', array('filter' => 'account_id', 'value' => $telegram_client['application_id']), true) ?>">Filter by Account</a>
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('telegram_clients', array('filter' => 'chat_id', 'value' => $telegram_client['chat_id']), true) ?>">Filter by Chat ID</a>
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('telegram_clients', array('filter' => 'user_id', 'value' => $telegram_client['user_id']), true) ?>">Filter by User ID</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?PHP
                                                }
                                                ?>

                                                </tbody>
                                            </table>
                                        </div>
                                        <?PHP
                                        if($Results['total_pages'] > 1)
                                        {
                                            $RedirectHref = $_GET;
                                            ?>
                                            <div class="wrapper mt-4">
                                                <div class="d-flex flex-column justify-content-center align-items-center">
                                                    <div class="p-2 my-flex-item">
                                                        <nav>
                                                            <ul class="pagination flat pagination-success flex-wrap">
                                                                <?PHP
                                                                if($Results['current_page'] == 1)
                                                                {
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link disabled" disabled>
                                                                            <i class="mdi mdi-chevron-left"></i>
                                                                        </a>
                                                                    </li>

                                                                    <?PHP
                                                                }
                                                                else
                                                                {
                                                                    $RedirectHref['page'] = $Results['current_page'] - 1;
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('authentication_access', $RedirectHref, true); ?>">
                                                                            <i class="mdi mdi-chevron-left"></i>
                                                                        </a>
                                                                    </li>
                                                                    <?PHP
                                                                }

                                                                $current_count = 1;
                                                                while(True)
                                                                {
                                                                    if($Results['current_page'] == $current_count)
                                                                    {
                                                                        ?>
                                                                        <li class="page-item active">
                                                                            <a class="page-link disabled" disabled><?PHP HTML::print($current_count); ?></a>
                                                                        </li>
                                                                        <?PHP
                                                                    }
                                                                    else
                                                                    {
                                                                        $RedirectHref['page'] = $current_count;
                                                                        ?>
                                                                        <li class="page-item">
                                                                            <a class="page-link" href="<?PHP DynamicalWeb::getRoute('authentication_access', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
                                                                        </li>
                                                                        <?PHP
                                                                    }

                                                                    if($Results['total_pages'] == $current_count)
                                                                    {
                                                                        break;
                                                                    }

                                                                    $current_count += 1;
                                                                }

                                                                if($Results['current_page'] == $Results['total_pages'])
                                                                {
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link disabled" disabled>
                                                                            <i class="mdi mdi-chevron-right"></i>
                                                                        </a>
                                                                    </li>

                                                                    <?PHP
                                                                }
                                                                else
                                                                {
                                                                    $RedirectHref['page'] = $Results['current_page'] + 1;
                                                                    ?>
                                                                    <li class="page-item">
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('authentication_access', $RedirectHref, true); ?>">
                                                                            <i class="mdi mdi-chevron-right"></i>
                                                                        </a>
                                                                    </li>
                                                                    <?PHP
                                                                }
                                                                ?>
                                                            </ul>
                                                        </nav>
                                                    </div>
                                                </div>
                                            </div>
                                            <?PHP
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP HTML::importScript('search_dialog'); ?>
                    <?PHP HTML::importScript('filter_dialog'); ?>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>