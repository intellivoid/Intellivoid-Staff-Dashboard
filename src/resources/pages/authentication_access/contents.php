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

        if($_GET['filter'] == 'application_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'application_id';
                $where_value = (int)$_GET['value'];
            }
        }
    }

    $Results = get_results($IntellivoidAccounts->database, 5000, 'authentication_access', 'id',
        QueryBuilder::select(
                'authentication_access', ['id', 'access_token', 'application_id', 'account_id', 'request_id', 'permissions', 'status', 'expires_timestamp', 'created_timestamp'],
                $where, $where_value, 'created_timestamp', SortBy::descending
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
        <title>Intellivoid Staff - COA Authentication Access</title>
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
                                        <h4 class="card-title">COA Authentication Access</h4>
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
                                        <?PHP
                                            if(count($Results['results']) > 0)
                                            {
                                                ?>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Access Token</th>
                                                            <th>Application</th>
                                                            <th>Account</th>
                                                            <th>Permissions</th>
                                                            <th>Status</th>
                                                            <th>Created</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $authentication_access)
                                                        {
                                                            $request_token = $authentication_access['access_token'];
                                                            $authentication_access['access_token'] = (strlen($authentication_access['access_token']) > 15) ? substr($authentication_access['access_token'], 0, 15) . '...' : $authentication_access['access_token'];
                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($authentication_access['id']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($request_token); ?>"><?PHP HTML::print($authentication_access['access_token']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" > <?PHP HTML::print($authentication_access['application_id']); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <?PHP render_app_dropdown($IntellivoidAccounts, $authentication_access['application_id']); ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($authentication_access['account_id']); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-account text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Account ID <?PHP HTML::print($authentication_access['account_id']); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $authentication_access['account_id']), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-pencil"></i>
                                                                                </a>
                                                                                <a href="<?PHP DynamicalWeb::getRoute('authentication_access', array('filter' => 'account_id', 'value' => $authentication_access['account_id']), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-filter"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                    $requested_permissions = ZiProto::decode($authentication_access['permissions']);

                                                                    HTML::print("<i class=\"mdi mdi-account-card-details\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Access to Username and Avatar\"></i>", false);

                                                                    if(in_array(AccountRequestPermissions::ViewEmailAddress, $requested_permissions))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-email pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Access to Email Address\"></i>", false);
                                                                    }

                                                                    if(in_array(AccountRequestPermissions::ReadPersonalInformation, $requested_permissions))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-account pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"View personal information\"></i>", false);
                                                                    }

                                                                    if(in_array(AccountRequestPermissions::EditPersonalInformation, $requested_permissions))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-account-edit pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Edit personal information\"></i>", false);
                                                                    }

                                                                    if(in_array(AccountRequestPermissions::TelegramNotifications, $requested_permissions))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-telegram pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Send notifications via Telegram\"></i>", false);
                                                                    }

                                                                    if(in_array(AccountRequestPermissions::MakePurchases, $requested_permissions))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-shopping pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Make purchases on the users behalf\"></i>", false);
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                    switch($authentication_access['status'])
                                                                    {
                                                                        case AuthenticationAccessStatus::Active:
                                                                            if((int)time() > (int)$authentication_access['expires_timestamp'])
                                                                            {
                                                                                HTML::print("<label class=\"badge badge-warning\">Expired</label>", false);
                                                                            }
                                                                            else
                                                                            {
                                                                                HTML::print("<label class=\"badge badge-success\">Active</label>", false);
                                                                            }
                                                                            break;

                                                                        case AuthenticationAccessStatus::Revoked:
                                                                            HTML::print("<label class=\"badge badge-danger\">Revoked</label>", false);
                                                                            break;

                                                                        default:
                                                                            HTML::print("<label class=\"badge badge-outline-primary\">Unknown</label>", false);
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $authentication_access['created_timestamp'])); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_authentication_access', array('id' => $authentication_access['id']), true); ?>">View Details</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $authentication_access['application_id']), true); ?>">Manage Application</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $authentication_access['application_id']), true); ?>">Manage Account</a>
                                                                            <div class="dropdown-divider"></div>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('authentication_access', array('filter' => 'application_id', 'value' => $authentication_access['application_id']), true) ?>">Filter by Application</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('authentication_access', array('filter' => 'account_id', 'value' => $authentication_access['account_id']), true) ?>">Filter by Account</a>
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
                                                <?PHP
                                            }
                                            else
                                            {
                                                ?>
                                                <div class="wrapper mt-4">
                                                    <div class="d-flex flex-column justify-content-center align-items-center" style="height:50vh;">
                                                        <div class="p-2 my-flex-item">
                                                            <h4>No Items</h4>
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