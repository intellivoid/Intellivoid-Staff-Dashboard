<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\LoginStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\KnownHost;
    use IntellivoidAccounts\Objects\UserLoginRecord;
use IntellivoidAPI\Abstracts\AccessRecordStatus;
use IntellivoidAPI\IntellivoidAPI;
use IntellivoidAPI\Objects\AccessRecord;
use IntellivoidAPI\Objects\RequestRecord;
use msqg\Abstracts\SortBy;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    Runtime::import('IntellivoidAPI');
    HTML::importScript('db_render_helper');
    HTML::importScript('process_search');

    $IntellivoidAPI = new IntellivoidAPI();

    $where = null;
    $where_value = null;

    if(isset($_GET['filter']))
    {
        if($_GET['filter'] == 'request_method')
        {
            if(isset($_GET['value']))
            {
                $where = 'request_method';
                $where_value = $IntellivoidAPI->getDatabase()->real_escape_string($_GET['value']);
            }
        }

        if($_GET['filter'] == 'version')
        {
            if(isset($_GET['value']))
            {
                $where = 'version';
                $where_value = $IntellivoidAPI->getDatabase()->real_escape_string($_GET['value']);
            }
        }

        if($_GET['filter'] == 'path')
        {
            if(isset($_GET['value']))
            {
                $where = 'path';
                $where_value = $IntellivoidAPI->getDatabase()->real_escape_string($_GET['value']);
            }
        }

        if($_GET['filter'] == 'ip_address')
        {
            if(isset($_GET['value']))
            {
                $where = 'ip_address';
                $where_value = $IntellivoidAPI->getDatabase()->real_escape_string($_GET['value']);
            }
        }

        if($_GET['filter'] == 'response_code')
        {
            if(isset($_GET['value']))
            {
                $where = 'response_code';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'access_record_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'access_record_id';
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

    $Results = get_results($IntellivoidAPI->getDatabase(), 5000, 'request_records', 'id',
        QueryBuilder::select(
                'request_records', ['id', 'reference_id', 'access_record_id', 'application_id', 'request_method', 'version', 'path', 'ip_address', 'response_code', 'response_time', 'timestamp'],
                $where, $where_value, 'timestamp', SortBy::descending
        ),
    $where, $where_value);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - API Request Records</title>
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
                                        <h4 class="card-title">API Request Records</h4>
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
                                                                <th>Reference ID</th>
                                                                <th>Request Method</th>
                                                                <th>Version</th>
                                                                <th>Path</th>
                                                                <th>IP Address</th>
                                                                <th>Response Code</th>
                                                                <th>Response Time</th>
                                                                <th>Access Record ID</th>
                                                                <th>Application ID</th>
                                                                <th>Timestamp</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $requestRecord)
                                                        {
                                                            $reference_id = $requestRecord['reference_id'];
                                                            $requestRecord['reference_id'] = (strlen($requestRecord['reference_id']) > 15) ? substr($requestRecord['reference_id'], 0, 15) . '...' : $requestRecord['reference_id'];
                                                            /** @var UserLoginRecord $loginRecord */
                                                            $requestRecordObject = RequestRecord::fromArray($requestRecord);

                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($requestRecordObject->ID); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($reference_id); ?>"><?PHP HTML::print($requestRecordObject->ReferenceID); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($requestRecordObject->RequestMethod); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($requestRecordObject->Version); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($requestRecordObject->Path); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($requestRecordObject->IPAddress); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                        if($requestRecordObject->ResponseCode >= 100)
                                                                        {
                                                                            $BadgeColor = "info";
                                                                        }
                                                                        if($requestRecordObject->ResponseCode >= 200)
                                                                        {
                                                                            $BadgeColor = "success";
                                                                        }
                                                                        if($requestRecordObject->ResponseCode >= 300)
                                                                        {
                                                                            $BadgeColor = "primary";
                                                                        }
                                                                        if($requestRecordObject->ResponseCode >= 400)
                                                                        {
                                                                            $BadgeColor = "warning";
                                                                        }
                                                                        if($requestRecordObject->ResponseCode >= 500)
                                                                        {
                                                                            $BadgeColor = "danger";
                                                                        }
                                                                    ?>
                                                                    <span class="badge badge-<?PHP HTML::print($BadgeColor); ?>"><?PHP HTML::print($requestRecordObject->ResponseCode); ?></span>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($requestRecordObject->ResponseTime); ?>ms</td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($requestRecordObject->AccessRecordID); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-application text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Access Record ID <?PHP HTML::print($requestRecordObject->AccessRecordID); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('api/view_access_record', array('id' => $requestRecordObject->AccessRecordID), true); ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-database-search"></i>
                                                                                </a>
                                                                                <a href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'access_record_id', 'value' => $requestRecordObject->AccessRecordID), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-filter"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($requestRecordObject->ApplicationID); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-application text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Application <?PHP HTML::print($requestRecordObject->ApplicationID); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $requestRecordObject->ApplicationID), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-database-search"></i>
                                                                                </a>
                                                                                <a href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'application_id', 'value' => $requestRecordObject->ApplicationID), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-filter"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"> <?PHP HTML::print(gmdate("j/m/Y, g:i a", $requestRecordObject->Timestamp)); ?> </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_request_record', array('id' => $requestRecordObject->ID), true); ?>">View Details</a>
                                                                            <div class="dropdown-divider"></div>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('api/view_access_record', array('id' => $requestRecordObject->AccessRecordID), true); ?>">View Access Record</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $requestRecordObject->ApplicationID), true); ?>">View Application</a>
                                                                            <a class="dropdown-item" href="#">View Exception</a>
                                                                            <div class="dropdown-divider"></div>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'request_method', 'value' => $requestRecordObject->RequestMethod), true); ?>">Filter by Request Method</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'version', 'value' => $requestRecordObject->Version), true); ?>">Filter by Version</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'path', 'value' => $requestRecordObject->Path), true); ?>">Filter by Path</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'ip_address', 'value' => $requestRecordObject->IPAddress), true); ?>">Filter by IP Address</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'response_code', 'value' => $requestRecordObject->ResponseCode), true); ?>">Filter by Response Code</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'access_record_id', 'value' => $requestRecordObject->AccessRecordID), true); ?>">Filter Access Record ID</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('request_records', array('filter' => 'application_id', 'value' => $requestRecordObject->ApplicationID), true); ?>">Filter Application ID</a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('request_records', $RedirectHref, true); ?>">
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
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('request_records', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('request_records', $RedirectHref, true); ?>">
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
                    <?PHP HTML::importScript('filter_dialog'); ?>
                    <?PHP HTML::importScript('search_dialog'); ?>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>