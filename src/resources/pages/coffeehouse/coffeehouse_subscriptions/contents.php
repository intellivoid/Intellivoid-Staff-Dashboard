<?PHP

    use CoffeeHouse\Abstracts\UserSubscriptionStatus;
    use CoffeeHouse\CoffeeHouse;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use msqg\Abstracts\SortBy;
    use msqg\QueryBuilder;

    Runtime::import('CoffeeHouse');
    HTML::importScript('db_render_helper');

    $CoffeeHouse = new CoffeeHouse();

    $where = null;
    $where_value = null;

    $Results = get_results($CoffeeHouse->getDatabase(), 5000, 'user_subscriptions', 'id',
        QueryBuilder::select(
                'user_subscriptions', ['id', 'account_id', 'subscription_id', 'access_record_id', 'status', 'created_timestamp'],
                $where, $where_value, 'created_timestamp', SortBy::descending
        ),
    $where, $where_value);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - CoffeeHouse User Subscriptions</title>
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
                                        <h4 class="card-title">CoffeeHouse Subscriptions</h4>
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
                                                            <th>Account ID</th>
                                                            <th>Subscription ID</th>
                                                            <th>Access Record ID</th>
                                                            <th>Status</th>
                                                            <th>Created Timestamp</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?PHP
                                                        foreach($Results['results'] as $subscription)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP HTML::print($subscription['id']); ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($subscription['account_id']); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-account text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Account ID <?PHP HTML::print($subscription['account_id']); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('id' => $subscription['account_id']), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-pencil"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print($subscription['subscription_id']); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <span  data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><?PHP HTML::print($subscription['access_record_id']); ?></span>
                                                                        <div class="dropdown-menu p-3">
                                                                            <div class="d-flex text-white">
                                                                                <i class="mdi mdi-account text-white icon-md"></i>
                                                                                <div class="d-flex flex-column ml-2 mr-5">
                                                                                    <h6 class="mb-0">Access Record ID <?PHP HTML::print($subscription['access_record_id']); ?></h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="border-top mt-3 mb-3"></div>
                                                                            <div class="row ml-auto">
                                                                                <a href="<?PHP DynamicalWeb::getRoute('api/view_access_record', array('id' => $subscription['access_record_id']), true) ?>" class="text-white pl-2">
                                                                                    <i class="mdi mdi-pencil"></i>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <?PHP
                                                                        switch($subscription['status'])
                                                                        {
                                                                            case UserSubscriptionStatus::Active:
                                                                                HTML::print("<label class=\"badge badge-success\">Active</label>", false);
                                                                                break;

                                                                            case UserSubscriptionStatus::Disabled:
                                                                                HTML::print("<label class=\"badge badge-danger\">Disabled</label>", false);
                                                                                break;

                                                                            default:
                                                                                HTML::print("<label class=\"badge badge-primary\">Unknown</label>", false);
                                                                                break;
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;"><?PHP HTML::print(date("F j, Y, g:i a", $subscription['created_timestamp'])); ?></td>
                                                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" href="#">Actions</a>
                                                                        <div class="dropdown-menu">
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('id' => $subscription['account_id']), true) ?>">Manage Account</a>
                                                                            <a class="dropdown-item" href="<?PHP DynamicalWeb::getRoute('api/view_access_record', array('id' => $subscription['access_record_id']), true) ?>">Manage Access Record</a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('coffeehouse_subscriptions', $RedirectHref, true); ?>">
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
                                                                                    <a class="page-link" href="<?PHP DynamicalWeb::getRoute('coffeehouse_subscriptions', $RedirectHref, true); ?>"><?PHP HTML::print($current_count); ?></a>
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
                                                                                <a class="page-link" href="<?PHP DynamicalWeb::getRoute('coffeehouse_subscriptions', $RedirectHref, true); ?>">
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
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>