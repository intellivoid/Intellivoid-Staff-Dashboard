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
use IntellivoidAPI\Objects\ExceptionRecord;
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
        if($_GET['filter'] == 'application_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'application_id';
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
    }

    $Results = get_results($IntellivoidAPI->getDatabase(), 5000, 'exception_records', 'id',
        QueryBuilder::select(
                'exception_records', ['id', 'request_record_id', 'application_id', 'access_record_id', 'message', 'timestamp'],
                $where, $where_value, 'timestamp', SortBy::descending
        ),
    $where, $where_value);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - API Exception Records</title>
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
                                        <h4 class="card-title">API Exception Records</h4>
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
                                                                <th>Request Record ID</th>
                                                                <th>Application ID</th>
                                                                <th>Access Record ID</th>
                                                                <th>Message</th>
                                                                <th>Timestamp</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $exceptionRecord)
                                                        {
                                                            $message = $exceptionRecord['message'];
                                                            $exceptionRecord['message'] = (strlen($exceptionRecord['message']) > 15) ? substr($exceptionRecord['message'], 0, 15) . '...' : $exceptionRecord['message'];
                                                            /** @var ExceptionRecord $exceptionRecordObject */
                                                            $exceptionRecordObject = ExceptionRecord::fromArray($exceptionRecord);

                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($exceptionRecordObject->ID); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($exceptionRecordObject->RequestRecordID); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-application text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0"><?PHP HTML::print($exceptionRecordObject->RequestRecordID); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="#" class="text-white pl-2">
                                                                                    <i class="mdi mdi-database-search"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($exceptionRecordObject->ApplicationID); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-application text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0"><?PHP HTML::print($exceptionRecordObject->ApplicationID); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $exceptionRecordObject->ApplicationID), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-database-search"></i>
                                                                                </a>
                                                                                <a href="<?PHP DynamicalWeb::getRoute('exception_records', array('filter' => 'application_id', 'value' => $exceptionRecordObject->ApplicationID), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-filter"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($exceptionRecordObject->AccessRecordID); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-application text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0"><?PHP HTML::print($exceptionRecordObject->AccessRecordID); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="#" class="text-white pl-2">
                                                                                    <i class="mdi mdi-database-search"></i>
                                                                                </a>
                                                                                <a href="<?PHP DynamicalWeb::getRoute('exception_records', array('filter' => 'access_record_id', 'value' => $exceptionRecordObject->AccessRecordID), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-filter"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($message); ?>"><?PHP HTML::print($exceptionRecordObject->Message); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"> <?PHP HTML::print(gmdate("j/m/Y, g:i a", $exceptionRecordObject->Timestamp)); ?> </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('view_exception_record', array('id' => $exceptionRecordObject->ID), true); ?>">View Details</a>
                                                                            <div class="dropdown-divider"></div>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('exception_records', array('filter' => 'application_id', 'value' => $exceptionRecordObject->ApplicationID), true); ?>">Filter by Application ID</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('exception_records', array('filter' => 'access_record_id', 'value' => $exceptionRecordObject->AccessRecordID), true); ?>">Filter by Access Record ID</a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('exception_records', $RedirectHref, true); ?>">
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
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('exception_records', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('exception_records', $RedirectHref, true); ?>">
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