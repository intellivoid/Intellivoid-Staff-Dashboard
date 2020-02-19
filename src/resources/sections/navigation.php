<?PHP

    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

    HTML::importScript('avatar');

    $UsernameSafe = ucfirst(WEB_ACCOUNT_USERNAME);
    if(strlen($UsernameSafe) > 16)
    {
        $UsernameSafe = substr($UsernameSafe, 0 ,16);
        $UsernameSafe .= "...";
    }

?>
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-dark">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="#">
           Intellivoid
        </a>
        <a class="navbar-brand brand-logo-mini" href="#">
            <img src="/assets/images/iv_logo.svg" alt="logo" />
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown d-none d-xl-inline-block">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <span class="profile-text"><?PHP HTML::print($UsernameSafe); ?></span>
                    <img class="img-xs" src="<?PHP HTML::print(getAvatarUrl(WEB_ACCOUNT_PUBID, 'normal')); ?>" alt="Profile image">
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown py-2" aria-labelledby="UserDropdown">
                    <a href="<?PHP DynamicalWeb::getRoute('logout', array(), true); ?>" class="dropdown-item">Close Session</a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>