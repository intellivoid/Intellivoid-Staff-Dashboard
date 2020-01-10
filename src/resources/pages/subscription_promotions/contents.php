<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
    use IntellivoidAccounts\Abstracts\AuthenticationRequestStatus;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
use IntellivoidAccounts\Abstracts\SubscriptionPlanStatus;
use IntellivoidAccounts\Abstracts\SubscriptionPromotionStatus;
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
        if($_GET['filter'] == 'subscription_plan_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'subscription_plan_id';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'affiliation_account_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'subscription_plan_id';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'status')
        {
            if(isset($_GET['value']))
            {
                $where = 'subscription_plan_id';
                $where_value = (int)$_GET['value'];
            }
        }
    }

    $Results = get_results($IntellivoidAccounts->database, 5000, 'subscription_promotions', 'id',
        QueryBuilder::select('subscription_promotions', ['id', 'public_id', 'promotion_code', 'subscription_plan_id', 'initial_price', 'cycle_price', 'affiliation_account_id', 'status', 'created_timestamp'],
            $where, $where_value, 'created_timestamp', SortBy::descending
        ),
    $where, $where_value);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Subscription Promotions</title>
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
                                        <h4 class="card-title">Subscription Promotions</h4>
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
                                                                <th>Public ID</th>
                                                                <th>Promotion Code</th>
                                                                <th>Subscription Plan ID</th>
                                                                <th>Initial Price</th>
                                                                <th>Cycle Price</th>
                                                                <th>Affiliation Account ID</th>
                                                                <th>Status</th>
                                                                <th>Created Timestamp</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?PHP
                                                            foreach($Results['results'] as $subscription_promotion)
                                                            {
                                                                $public_id = $subscription_promotion['public_id'];
                                                                $subscription_promotion['public_id'] = (strlen($subscription_promotion['public_id']) > 15) ? substr($subscription_promotion['public_id'], 0, 15) . '...' : $subscription_promotion['public_id'];
                                                                ?>
                                                                <tr>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($subscription_promotion['id']); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($public_id); ?>"><?PHP HTML::print($subscription_promotion['public_id']); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($subscription_promotion['promotion_code']); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <div class="dropdown">
                                                                            <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($subscription_promotion['subscription_plan_id']); ?></span>
                                                                            <div class="dropdown-menu p-3">
                                                                                <div class="d-flex text-white">
                                                                                    <i class="mdi mdi-application text-white icon-md"></i>
                                                                                    <div class="d-flex flex-column ml-2 mr-5">
                                                                                        <h6 class="mb-0"><?PHP HTML::print($subscription_promotion['subscription_plan_id']); ?></h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="border-top mt-3 mb-3"></div>
                                                                                <div class="row ml-auto">
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('manage_subscription_plan', array('id' => $subscription_promotion['subscription_plan_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-database-search"></i>
                                                                                    </a>
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('subscription_promotions', array('filter' => 'subscription_plan_id', 'value' => $subscription_promotion['subscription_plan_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-filter"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">$<?PHP HTML::print($subscription_promotion['initial_price']); ?> USD</td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">$<?PHP HTML::print($subscription_promotion['cycle_price']); ?> USD</td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <div class="dropdown">
                                                                            <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($subscription_promotion['affiliation_account_id']); ?></span>
                                                                            <div class="dropdown-menu p-3">
                                                                                <div class="d-flex text-white">
                                                                                    <i class="mdi mdi-application text-white icon-md"></i>
                                                                                    <div class="d-flex flex-column ml-2 mr-5">
                                                                                        <h6 class="mb-0"><?PHP HTML::print($subscription_promotion['affiliation_account_id']); ?></h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="border-top mt-3 mb-3"></div>
                                                                                <div class="row ml-auto">
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $subscription_promotion['affiliation_account_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-database-search"></i>
                                                                                    </a>
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('subscription_promotions', array('filter' => 'affiliation_account_id', 'value' => $subscription_promotion['affiliation_account_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-filter"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <?PHP
                                                                        switch($subscription_promotion['status'])
                                                                        {
                                                                            case SubscriptionPromotionStatus::Active:
                                                                                HTML::print("<label class=\"badge badge-success\">Active</label>", false);
                                                                                break;

                                                                            case SubscriptionPromotionStatus::Disabled:
                                                                                HTML::print("<label class=\"badge badge-warning\">Disabled</label>", false);
                                                                                break;

                                                                            case SubscriptionPromotionStatus::Expired:
                                                                                HTML::print("<label class=\"badge badge-danger\">Expired</label>", false);
                                                                                break;

                                                                            default:
                                                                                HTML::print("<label class=\"badge badge-outline-primary\">Unknown</label>", false);
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $subscription_promotion['created_timestamp'])); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <div class="dropdown">
                                                                            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('manage_subscription_promotion', array('id' => $subscription_promotion['id']), true); ?>">Manage Promotion</a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('subscription_promotions', $RedirectHref, true); ?>">
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
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('subscription_promotions', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('subscription_promotions', $RedirectHref, true); ?>">
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