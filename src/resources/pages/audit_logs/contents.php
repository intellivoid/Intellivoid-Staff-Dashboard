<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
use IntellivoidAccounts\Abstracts\AuditEventType;
use IntellivoidAccounts\Abstracts\AuthenticationAccessStatus;
use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAccounts\Objects\COA\Application;
use IntellivoidAccounts\Objects\LocationData;
use IntellivoidAccounts\Objects\TelegramClient\Chat;
use IntellivoidAccounts\Objects\TelegramClient\User;
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
    }

    $Results = get_results($IntellivoidAccounts->database, 5000, 'users_audit', 'id',
        QueryBuilder::select(
                'users_audit', ['id', 'account_id', 'event_type', 'timestamp'],
                $where, $where_value, 'timestamp', SortBy::descending
        ),
    $where, $where_value);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - Audit Logs</title>
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
                                        <h4 class="card-title">Audit Logs</h4>
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
                                                        <th>Account ID</th>
                                                        <th>Event Type</th>
                                                        <th>Timestamp</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?PHP
                                                    foreach($Results['results'] as $audit_log)
                                                    {
                                                    ?>
                                                    <tr>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($audit_log['id']); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($audit_log['account_id']); ?></span>
                                                                <div class="dropdown-menu p-3">
                                                                    <div class="d-flex text-white">
                                                                        <i class="mdi mdi-account text-white icon-md"></i>
                                                                        <div class="d-flex flex-column ml-2 mr-5">
                                                                            <h6 class="mb-0">Account ID <?PHP HTML::print($audit_log['account_id']); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border-top mt-3 mb-3"></div>
                                                                    <div class="row ml-auto">
                                                                        <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $audit_log['account_id']), true) ?>" class="text-white">
                                                                            <i class="mdi mdi-pencil"></i>
                                                                        </a>
                                                                        <a href="<?PHP DynamicalWeb::getRoute('audit_logs', array('filter' => 'account_id', 'value' => $audit_log['account_id']), true) ?>" class="text-white pl-2">
                                                                            <i class="mdi mdi-filter"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <?PHP
                                                                switch($audit_log['event_type'])
                                                                {
                                                                    case AuditEventType::NewLoginDetected:
                                                                        HTML::print("<div class=\"badge badge-primary\">", false);
                                                                        HTML::print("New Login Detected");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::PasswordUpdated:
                                                                        HTML::print("<div class=\"badge badge-primary\">", false);
                                                                        HTML::print("Password Updated");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::PersonalInformationUpdated:
                                                                        HTML::print("<div class=\"badge badge-primary\">", false);
                                                                        HTML::print("Personal Information Updated");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::EmailUpdated:
                                                                        HTML::print("<div class=\"badge badge-primary\">", false);
                                                                        HTML::print("Email Updated");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::MobileVerificationEnabled:
                                                                        HTML::print("<div class=\"badge badge-success\">", false);
                                                                        HTML::print("Mobile Verification Enabled");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::MobileVerificationDisabled:
                                                                        HTML::print("<div class=\"badge badge-danger\">", false);
                                                                        HTML::print("Mobile Verification Disabled");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::RecoveryCodesEnabled:
                                                                        HTML::print("<div class=\"badge badge-success\">", false);
                                                                        HTML::print("Recovery Codes Enabled");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::RecoveryCodesDisabled:
                                                                        HTML::print("<div class=\"badge badge-danger\">", false);
                                                                        HTML::print("Recovery Codes Disabled");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::TelegramVerificationEnabled:
                                                                        HTML::print("<div class=\"badge badge-success\">", false);
                                                                        HTML::print("Telegram Verification Enabled");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::TelegramVerificationDisabled:
                                                                        HTML::print("<div class=\"badge badge-danger\">", false);
                                                                        HTML::print("Telegram Verification Disabled");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::ApplicationCreated:
                                                                        HTML::print("<div class=\"badge badge-primary\">", false);
                                                                        HTML::print("Application Created");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    case AuditEventType::NewLoginLocationDetected:
                                                                        HTML::print("<div class=\"badge badge-primary\">", false);
                                                                        HTML::print("New Login Location Detected");
                                                                        HTML::print("</div>", false);
                                                                        break;

                                                                    default:
                                                                        HTML::print("<div class=\"badge badge-primary\">", false);
                                                                        HTML::print("Unknown (" . $audit_log['event_type'] . ")");
                                                                        HTML::print("</div>", false);
                                                                        break;
                                                                }
                                                            ?>
                                                        </td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $audit_log['timestamp'])); ?></td>
                                                        <td style="padding-top: 10px; padding-bottom: 10px;">
                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('audit_logs', array('id' => $audit_log['id'], 'filter' => 'account_id', 'value' => $audit_log['account_id']), true); ?>">Filter by Account ID</a>
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
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('audit_logs', $RedirectHref, true); ?>">
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
                                                                            <a class="page-link" href="<?PHP DynamicalWeb::getRoute('audit_logs', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                        <a class="page-link" href="<?PHP DynamicalWeb::getRoute('audit_logs', $RedirectHref, true); ?>">
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