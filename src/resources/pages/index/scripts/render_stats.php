<?PHP

    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');
    HTML::importScript('db_render_helper');

    $IntellivoidAccounts = new IntellivoidAccounts();

    $TotalAccounts = get_total_items($IntellivoidAccounts->database, 'users', 'id');
    $LatestAccounts = get_total_items_by_operator($IntellivoidAccounts->database, 'users', 'id', 'creation_date', '>', (time() - 86400));

?>
<div class="col-md-4 col-sm-12 grid-margin stretch-card">
    <div class="card card-statistics bg-green-gradient">
        <div class="card-body">
            <div class="clearfix">
                <div class="float-left">
                    <i class="mdi mdi-account icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Total Accounts</p>
                    <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0"><?PHP HTML::print(number_format($TotalAccounts)); ?></h3>
                    </div>
                </div>
            </div>
            <p class="mt-3 mb-0">
                <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> <?PHP HTML::print(number_format($LatestAccounts)); ?> created in the last 24 Hours
            </p>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-12 grid-margin stretch-card">
    <div class="card card-statistics bg-orange-gradient">
        <div class="card-body">
            <div class="clearfix">
                <div class="float-left">
                    <i class="mdi mdi-receipt icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Orders</p>
                    <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">3455</h3>
                    </div>
                </div>
            </div>
            <p class="mt-3 mb-0">
                <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Product-wise sales </p>
        </div>
    </div>
</div>
<div class="col-md-4 col-sm-12 grid-margin stretch-card">
    <div class="card card-statistics bg-blue-gradient">
        <div class="card-body">
            <div class="clearfix">
                <div class="float-left">
                    <i class="mdi mdi-poll-box icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Sales</p>
                    <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">5693</h3>
                    </div>
                </div>
            </div>
            <p class="mt-3 mb-0">
                <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Weekly Sales </p>
        </div>
    </div>
</div>