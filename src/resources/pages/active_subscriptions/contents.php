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
        if($_GET['filter'] == 'account_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'account_id';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'subscription_plan_id')
        {
            if(isset($_GET['value']))
            {
                $where = 'subscription_plan_id';
                $where_value = (int)$_GET['value'];
            }
        }

        if($_GET['filter'] == 'active')
        {
            if(isset($_GET['value']))
            {
                $where = 'active';
                $where_value = (int)$_GET['value'];
            }
        }
    }

    $Results = get_results($IntellivoidAccounts->database, 5000, 'subscriptions', 'id',
        QueryBuilder::select('subscriptions', ['id', 'public_id', 'account_id', 'subscription_plan_id', 'active', 'billing_cycle', 'next_billing_cycle', 'created_timestamp'],
            $where, $where_value, 'created_timestamp', SortBy::descending
        ),
    $where, $where_value);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Active Subscriptions</title>
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
                                                                <th>Account ID</th>
                                                                <th>Subscription Plan ID</th>
                                                                <th>Billing Cycle</th>
                                                                <th>Next Billing Cycle</th>
                                                                <th>Active</th>
                                                                <th>Created Timestamp</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?PHP
                                                            foreach($Results['results'] as $active_subscription)
                                                            {
                                                                $public_id = $active_subscription['public_id'];
                                                                $active_subscription['public_id'] = (strlen($active_subscription['public_id']) > 15) ? substr($active_subscription['public_id'], 0, 15) . '...' : $active_subscription['public_id'];
                                                                ?>
                                                                <tr>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($active_subscription['id']); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;" data-toggle="tooltip" data-placement="bottom" title="<?PHP HTML::print($public_id); ?>"><?PHP HTML::print($active_subscription['public_id']); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <div class="dropdown">
                                                                            <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($active_subscription['account_id']); ?></span>
                                                                            <div class="dropdown-menu p-3">
                                                                                <div class="d-flex text-white">
                                                                                    <i class="mdi mdi-application text-white icon-md"></i>
                                                                                    <div class="d-flex flex-column ml-2 mr-5">
                                                                                        <h6 class="mb-0"><?PHP HTML::print($active_subscription['account_id']); ?></h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="border-top mt-3 mb-3"></div>
                                                                                <div class="row ml-auto">
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $active_subscription['account_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-database-search"></i>
                                                                                    </a>
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('active_subscriptions', array('filter' => 'account_id', 'value' => $active_subscription['account_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-filter"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <div class="dropdown">
                                                                            <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($active_subscription['subscription_plan_id']); ?></span>
                                                                            <div class="dropdown-menu p-3">
                                                                                <div class="d-flex text-white">
                                                                                    <i class="mdi mdi-application text-white icon-md"></i>
                                                                                    <div class="d-flex flex-column ml-2 mr-5">
                                                                                        <h6 class="mb-0"><?PHP HTML::print($active_subscription['account_id']); ?></h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="border-top mt-3 mb-3"></div>
                                                                                <div class="row ml-auto">
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('manage_subscription_plan', array('id' => $active_subscription['subscription_plan_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-database-search"></i>
                                                                                    </a>
                                                                                    <a href="<?PHP DynamicalWeb::getRoute('active_subscriptions', array('filter' => 'account_id', 'value' => $active_subscription['subscription_plan_id']), true) ?>" class="text-white pl-2">
                                                                                        <i class="mdi mdi-filter"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $active_subscription['billing_cycle'])); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $active_subscription['next_billing_cycle'])); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <?PHP
                                                                            if($active_subscription['active'])
                                                                            {
                                                                                HTML::print("True");
                                                                            }
                                                                            else
                                                                            {
                                                                                HTML::print("False");
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $active_subscription['created_timestamp'])); ?></td>
                                                                    <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                        <div class="dropdown">
                                                                            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item" href="#">View Details</a>
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