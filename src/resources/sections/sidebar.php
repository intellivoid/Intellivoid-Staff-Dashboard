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
                </ul>
            </div>
        </li>
    </ul>
</nav>