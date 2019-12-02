<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\LoginStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAccounts\Objects\COA\Application;
use IntellivoidAccounts\Objects\KnownHost;
use IntellivoidAccounts\Objects\UserLoginRecord;
use msqg\Abstracts\SortBy;
use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    Runtime::import('IntellivoidAccounts');
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

        if($_GET['filter'] == 'origin')
        {
            if(isset($_GET['value']))
            {
                $where = 'origin';
                $where_value = $IntellivoidAccounts->database->real_escape_string($_GET['value']);
            }
        }

        if($_GET['filter'] == 'host_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'host_id';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'status')
        {
            if(isset($_GET['value']))
            {
                $where = 'status';
                $where_value = (int)$_GET['value'];
            }
        }
    }

    $Results = get_results($IntellivoidAccounts->database, 5000, 'users_logins', 'id',
        QueryBuilder::select(
                'users_logins', ['id', 'public_id', 'origin', 'host_id', 'user_agent', 'account_id', 'status' ,'timestamp'],
                $where, $where_value, 'timestamp', SortBy::descending
        ),
    $where, $where_value);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - Login Records</title>
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
                                        <h4 class="card-title">User Login Records</h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" data-toggle="modal" data-target="#filterDialog" type="button">
                                                <i class="mdi mdi-filter"></i>
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
                                                        <th>Origin</th>
                                                        <th>Host ID</th>
                                                        <th>Browser</th>
                                                        <th>IP Address</th>
                                                        <th>Account ID</th>
                                                        <th>Status</th>
                                                        <th>Timestamp</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?PHP
                                                foreach($Results['results'] as $loginRecordRow)
                                                {
                                                    $public_id = $loginRecordRow['public_id'];
                                                    $loginRecordRow['public_id'] = (strlen($loginRecordRow['public_id']) > 15) ? substr($loginRecordRow['public_id'], 0, 15) . '...' : $loginRecordRow['public_id'];
                                                    $loginRecordRow['user_agent'] = ZiProto::decode($loginRecordRow['user_agent']);
                                                    /** @var UserLoginRecord $loginRecord */
                                                    $loginRecord = UserLoginRecord::fromArray($loginRecordRow);

                                                    if(isset(DynamicalWeb::$globalObjects["host_" . $loginRecord->HostID]) == false)
                                                    {
                                                        $KnownHost = $IntellivoidAccounts->getKnownHostsManager()->getHost(KnownHostsSearchMethod::byId, $loginRecord->HostID);
                                                        DynamicalWeb::setMemoryObject('host_' . $loginRecord->HostID, $KnownHost);
                                                    }

                                                    $Details = $loginRecord->UserAgent->Platform;
                                                    $Details .= ' ' . $loginRecord->UserAgent->Browser;
                                                    $Details .= ' ' . $loginRecord->UserAgent->Version;

                                                    if($KnownHost->LocationData->CountryName == null)
                                                    {
                                                        $LocationDetails = "Unknown";
                                                    }
                                                    else
                                                    {
                                                        if(isset($KnownHost->LocationData->City))
                                                        {
                                                            $LocationDetails = $KnownHost->LocationData->City;
                                                            $LocationDetails .= ' ' . $KnownHost->LocationData->CountryName;
                                                        }
                                                        else
                                                        {
                                                            $LocationDetails = $KnownHost->LocationData->CountryName;
                                                        }

                                                        if(isset($KnownHost->LocationData->ZipCode))
                                                        {
                                                            $LocationDetails .= ' (' . $KnownHost->LocationData->ZipCode . ')';
                                                        }
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($loginRecord->ID); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($public_id); ?>"><?PHP HTML::print($loginRecord->PublicID); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($loginRecord->Origin); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-application text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <h6 class="mb-0"><?PHP HTML::print($loginRecord->Origin); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'origin', 'value' => $loginRecord->Origin), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($loginRecord->HostID); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-account-network text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <h6 class="mb-0">Host ID <?PHP HTML::print($loginRecord->HostID); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('view_known_host', array('id' => $loginRecord->HostID), true) ?>" class="text-white">
                                                                            <i class="mdi mdi-pencil"></i>
                                                                        </a>
                                                                        <a href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'host_id', 'value' => $loginRecord->HostID), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?PHP HTML::print($Details); ?>">
                                                            <?PHP
                                                            switch($loginRecord->UserAgent->Platform)
                                                            {
                                                                case 'Chrome OS':
                                                                case 'Macintosh':
                                                                case 'Linux':
                                                                case 'Windows':
                                                                    HTML::print("<i class=\"mdi mdi-laptop pr-1\"></i>", false);
                                                                    break;

                                                                case 'iPad':
                                                                case 'iPod Touch':
                                                                case 'iPad / iPod Touch':
                                                                case 'Windows Phone OS':
                                                                case 'Kindle':
                                                                case 'Kindle Fire':
                                                                case 'BlackBerry':
                                                                case 'Playbook':
                                                                case 'Tizen':
                                                                case 'iPhone':
                                                                case 'Android':
                                                                    HTML::print("<i class=\"mdi mdi-cellphone-iphone pr-1\"></i>", false);
                                                                    break;

                                                                default:
                                                                    HTML::print("<i class=\"mdi mdi-monitor pr-1\"></i>", false);
                                                                    break;
                                                            }
                                                            HTML::print($loginRecord->UserAgent->Browser);
                                                            ?>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?PHP HTML::print($LocationDetails); ?>">
                                                            <div class="dropdown">
                                                                <?PHP
                                                                if($KnownHost->LocationData->CountryName == null)
                                                                {
                                                                    HTML::print("<i class=\"mdi mdi-map-marker-off mr-2\"></i>", false);
                                                                }
                                                                else
                                                                {
                                                                    $CountryCode = strtolower($KnownHost->LocationData->CountryCode);
                                                                    HTML::print("<i class=\"flag-icon flag-icon-$CountryCode mr-1\" title=\"$CountryCode\"></i>", false);
                                                                }
                                                                ?>
                                                                <span class="pl-1" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                                                                    <?PHP
                                                                        /** @var KnownHost $KnowHost */
                                                                        $KnowHost = DynamicalWeb::getMemoryObject('host_' . $loginRecord->HostID);
                                                                        HTML::print($KnownHost->IpAddress);
                                                                    ?>
                                                                </span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-account text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <h6 class="mb-0">Host ID <?PHP HTML::print($loginRecord->HostID); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('view_known_host', array('id' => $loginRecord->HostID), true) ?>" class="text-white">
                                                                            <i class="mdi mdi-pencil"></i>
                                                                        </a>
                                                                        <a href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'host_id', 'value' => $loginRecord->HostID), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($loginRecord->AccountID); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-account text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <h6 class="mb-0">Account ID <?PHP HTML::print($loginRecord->AccountID); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $loginRecord->AccountID), true) ?>" class="text-white">
                                                                            <i class="mdi mdi-pencil"></i>
                                                                        </a>
                                                                        <a href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'account_id', 'value' => $loginRecord->AccountID), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                                                                    <?PHP
                                                                    switch($loginRecord->Status)
                                                                    {
                                                                        case LoginStatus::Successful:
                                                                            HTML::print("<div class=\"badge badge-success\">", false);
                                                                            HTML::print("Successful");
                                                                            HTML::print("</div>", false);
                                                                            break;

                                                                        case LoginStatus::IncorrectCredentials:
                                                                            HTML::print("<div class=\"badge badge-warning\">", false);
                                                                            HTML::print("Incorrect Credentials");
                                                                            HTML::print("</div>", false);
                                                                            break;

                                                                        case LoginStatus::VerificationFailed:
                                                                            HTML::print("<div class=\"badge badge-warning\">", false);
                                                                            HTML::print("Verification Failed");
                                                                            HTML::print("</div>", false);
                                                                            break;

                                                                        case LoginStatus::UntrustedIpBlocked:
                                                                            HTML::print("<div class=\"badge badge-danger\">", false);
                                                                            HTML::print("Untrusted IP Blocked");
                                                                            HTML::print("</div>", false);
                                                                            break;

                                                                        case LoginStatus::BlockedSuspiciousActivities:
                                                                            HTML::print("<div class=\"badge badge-danger\">", false);
                                                                            HTML::print("Suspicious Activity Blocked");
                                                                            HTML::print("</div>", false);
                                                                            break;

                                                                        default:
                                                                            HTML::print("<div class=\"badge badge-info\">", false);
                                                                            HTML::print("Unknown");
                                                                            HTML::print("</div>", false);
                                                                            break;
                                                                    }
                                                                    ?>
                                                                </span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <?PHP
                                                                        switch($loginRecord->Status)
                                                                        {
                                                                            case LoginStatus::Successful:
                                                                                ?>
                                                                                <i class="mdi mdi-check text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Successful Login</h6>
                                                                                </div>
                                                                                <?PHP
                                                                                break;

                                                                            case LoginStatus::IncorrectCredentials:
                                                                                ?>
                                                                                <i class="mdi mdi-block-helper text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Incorrect Credentials</h6>
                                                                                </div>
                                                                                <?PHP
                                                                                break;

                                                                            case LoginStatus::VerificationFailed:
                                                                                ?>
                                                                                <i class="mdi mdi-alert text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Verification Failed</h6>
                                                                                </div>
                                                                                <?PHP
                                                                                break;

                                                                            case LoginStatus::UntrustedIpBlocked:
                                                                                ?>
                                                                                <i class="mdi mdi-block-helper text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Untrusted IP Blocked</h6>
                                                                                </div>
                                                                                <?PHP
                                                                                break;

                                                                            case LoginStatus::BlockedSuspiciousActivities:
                                                                                ?>
                                                                                <i class="mdi mdi-block-helper text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Blocked Suspicious Activities</h6>
                                                                                </div>
                                                                                <?PHP
                                                                                break;

                                                                            default:
                                                                                ?>
                                                                                <i class="mdi mdi-help text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Unknown</h6>
                                                                                </div>
                                                                                <?PHP
                                                                                break;
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'status', 'value' => $loginRecord->Status), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td style="padding-top: 10px; padding-bottom: 10px;"> <?PHP HTML::print(gmdate("j/m/Y, g:i a", $loginRecord->Timestamp)); ?> </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'host_id', 'value' => $loginRecord->HostID), true); ?>">Filter by Host</a>
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'account_id', 'value' => $loginRecord->AccountID), true); ?>">Filter by Account</a>
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'status', 'value' => $loginRecord->Status), true); ?>">Filter by Status</a>
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('login_records', array('filter' => 'origin', 'value' => $loginRecord->Origin), true); ?>">Filter by Origin</a>
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
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('login_records', $RedirectHref, true); ?>">
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
                                                                            <a class="page-link" href="<?PHP DynamicalWeb::getRoute('login_records', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('login_records', $RedirectHref, true); ?>">
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
                    <?PHP HTML::importScript('filter_dialog'); ?>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>