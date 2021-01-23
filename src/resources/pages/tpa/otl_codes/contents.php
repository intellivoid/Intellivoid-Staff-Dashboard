<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\OtlStatus;
    use IntellivoidAccounts\IntellivoidAccounts;
    use msqg\Abstracts\SortBy;
    use msqg\QueryBuilder;

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

        if($_GET['filter'] == 'vendor')
        {
            if(isset($_GET['value']))
            {
                $where = 'vendor';
                $where_value = $IntellivoidAccounts->database->real_escape_string($_GET['value']);
            }
        }
    }

    $Results = get_results($IntellivoidAccounts->database, 5000, 'otl_codes', 'id',
        QueryBuilder::select('otl_codes', ['id', 'code', 'vendor', 'account_id', 'status', 'expires' ,'created'],
            $where, $where_value, 'created', SortBy::descending
        ),
    $where, $where_value);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - OTL Codes</title>
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
                                        <h4 class="card-title">OTL Codes</h4>
                                        <div class="wrapper d-flex align-items-center">
                                            <button class="btn btn-transparent icon-btn arrow-disabled pl-2 pr-2 text-white text-small" data-toggle="modal" data-target="#filterDialog" type="button">
                                                <i class="mdi mdi-filter"></i>
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
                                                            <th>Code</th>
                                                            <th>Vendor</th>
                                                            <th>Account ID</th>
                                                            <th>Status</th>
                                                            <th>Expires</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $otl_record)
                                                        {
                                                            $otl_code = $otl_record['code'];
                                                            $otl_record['code'] = (strlen($otl_record['code']) > 15) ? substr($otl_record['code'], 0, 15) . '...' : $otl_record['code'];
                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($otl_record['id']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($otl_code); ?>"><?PHP HTML::print($otl_record['code']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($otl_record['vendor']); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-account text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0"><?PHP HTML::print($otl_record['vendor']); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('tpa/otl_codes', array('filter' => 'vendor', 'value' => $otl_record['vendor']), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-filter"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($otl_record['account_id']); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-account text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Account ID <?PHP HTML::print($otl_record['account_id']); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('id' => $otl_record['account_id']), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-pencil"></i>
                                                                                </a>
                                                                                <a href="<?PHP DynamicalWeb::getRoute('tpa/otl_codes', array('filter' => 'account_id', 'value' => $otl_record['account_id']), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-filter"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                    switch($otl_record['status'])
                                                                    {
                                                                        case OtlStatus::Available:
                                                                            if((int)time() > (int)$otl_record['expires'])
                                                                            {
                                                                                HTML::print("<label class=\"badge badge-warning\">Expired</label>", false);
                                                                            }
                                                                            else
                                                                            {
                                                                                HTML::print("<label class=\"badge badge-success\">Active</label>", false);
                                                                            }
                                                                            break;

                                                                        case OtlStatus::Used:
                                                                            HTML::print("<label class=\"badge badge-success\">Used</label>", false);
                                                                            break;

                                                                        case OtlStatus::Unavailable:
                                                                            HTML::print("<label class=\"badge badge-danger\">Unavailable</label>", false);
                                                                            break;

                                                                        default:
                                                                            HTML::print("<label class=\"badge badge-outline-primary\">Unknown</label>", false);
                                                                            break;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $otl_record['expires'])); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('tpa/otl_codes', array('filter' => 'account_id', 'value' => $otl_record['account_id']), true) ?>">Filter by Account ID</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('tpa/otl_codes', array('filter' => 'vendor', 'value' => $otl_record['vendor']), true) ?>">Filter by Vendor</a>
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
                                                                            $RedirectHref['page'] = $Results['current_page'] -1
                                                                            ?>
                                                                            <li class="page-item">
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('tpa/otl_codes', $RedirectHref, true); ?>">
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
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('tpa/otl_codes', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('tpa/otl_codes', $RedirectHref, true); ?>">
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
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>