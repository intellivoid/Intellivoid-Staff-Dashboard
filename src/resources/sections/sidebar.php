<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

    $UsernameSafe = ucfirst(WEB_ACCOUNT_USERNAME);
    if(strlen($UsernameSafe) > 16)
    {
        $UsernameSafe = substr($UsernameSafe, 0 ,16);
        $UsernameSafe .= "...";
    }

?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        <img src="<?PHP HTML::print(getAvatarUrl(WEB_ACCOUNT_PUBID, 'normal')); ?>" alt="profile image"> </div>
                    <div class="text-wrapper">
                        <p class="profile-name"><?PHP HTML::print($UsernameSafe); ?></p>
                        <div>
                            <small class="designation text-muted">Staff</small>
                            <span class="status-indicator online"></span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('index', [], true); ?>">
                <i class="menu-icon mdi mdi-view-dashboard"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('support_tickets', [], true); ?>">
                <i class="menu-icon mdi mdi-ticket"></i>
                <span class="menu-title">Support Tickets</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#cloud-dropdown" aria-expanded="false" aria-controls="cloud-dropdown">
                <i class="menu-icon mdi mdi-cloud"></i>
                <span class="menu-title">Cloud</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="cloud-dropdown">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('accounts', [], true); ?>">Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('applications', [], true); ?>">Applications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('devices', [], true); ?>">Devices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('known_hosts', [], true); ?>">Known Hosts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('login_records', [], true); ?>">Login Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('audit_logs', [], true); ?>">Audit Logs</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#finance-dropdown" aria-expanded="false" aria-controls="finance-dropdown">
                <i class="menu-icon mdi mdi-currency-usd"></i>
                <span class="menu-title">Finance</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="finance-dropdown">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('subscription_plans', [], true); ?>">Subscription Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('subscription_promotions', [], true); ?>">Subscription Promotions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('telegram_clients', [], true); ?>">Active Subscriptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('telegram_clients', [], true); ?>">Transaction Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('telegram_clients', [], true); ?>">Create Transaction</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#coa-dropdown" aria-expanded="false" aria-controls="coa-dropdown">
                <i class="menu-icon mdi mdi-lock"></i>
                <span class="menu-title">COA</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="coa-dropdown">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('authentication_requests', [], true); ?>">Authentication Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('authentication_access', [], true); ?>">Authentication Access</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('application_access', [], true); ?>">Application Access</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#api-dropdown" aria-expanded="false" aria-controls="api-dropdown">
                <i class="menu-icon mdi mdi-code-brackets"></i>
                <span class="menu-title">API Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="api-dropdown">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('access_records', [], true); ?>">Access Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('request_records', [], true); ?>">Request Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('exception_records', [], true); ?>">Exceptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('access_key_changes', [], true); ?>">Access Key Changes</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tpa-dropdown" aria-expanded="false" aria-controls="tpa-dropdown">
                <i class="menu-icon mdi mdi-network"></i>
                <span class="menu-title">Third-Party Access</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tpa-dropdown">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('telegram_clients', [], true); ?>">Telegram Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?PHP DynamicalWeb::getRoute('otl_codes', [], true); ?>">OTL Codes</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>