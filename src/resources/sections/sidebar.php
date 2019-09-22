<?PHP

    use DynamicalWeb\HTML;

    $UsernameSafe = ucfirst(WEB_ACCOUNT_USERNAME);
    if(strlen($UsernameSafe) > 16)
    {
        $UsernameSafe = substr($UsernameSafe, 0 ,16);
        $UsernameSafe .= "...";
    }

?>
<div id="right-sidebar" class="settings-panel">
    <i class="settings-close mdi mdi-close"></i>
    <div class="d-flex align-items-center justify-content-between border-bottom">
        <p class="settings-heading font-weight-bold border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
    </div>
    <ul class="chat-list">
        <li class="list active">
            <div class="profile">
                <img src="#" alt="image">
                <span class="online"></span>
            </div>
            <div class="info">
                <p></p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">19 min</small>
        </li>
        <li class="list">
            <div class="profile">
                <img src="/assets/images/faces/face2.jpg" alt="image">
                <span class="offline"></span>
            </div>
            <div class="info">
                <div class="wrapper d-flex">
                    <p>Catherine</p>
                </div>
                <p>Away</p>
            </div>
            <div class="badge badge-success badge-pill my-auto mx-2">4</div>
            <small class="text-muted my-auto">23 min</small>
        </li>
        <li class="list">
            <div class="profile">
                <img src="/assets/images/faces/face3.jpg" alt="image">
                <span class="online"></span>
            </div>
            <div class="info">
                <p>Daniel Russell</p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">14 min</small>
        </li>
        <li class="list">
            <div class="profile">
                <img src="/assets/images/faces/face4.jpg" alt="image">
                <span class="offline"></span>
            </div>
            <div class="info">
                <p>James Richardson</p>
                <p>Away</p>
            </div>
            <small class="text-muted my-auto">2 min</small>
        </li>
        <li class="list">
            <div class="profile">
                <img src="/assets/images/faces/face5.jpg" alt="image">
                <span class="online"></span>
            </div>
            <div class="info">
                <p>Madeline Kennedy</p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">5 min</small>
        </li>
        <li class="list">
            <div class="profile">
                <img src="/assets/images/faces/face6.jpg" alt="image">
                <span class="online"></span>
            </div>
            <div class="info">
                <p>Sarah Graves</p>
                <p>Available</p>
            </div>
            <small class="text-muted my-auto">47 min</small>
        </li>
    </ul>
</div>
<!-- partial -->
<!-- partial:../../partials/_sidebar.html -->
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
                            <small class="designation text-muted">Manager</small>
                            <span class="status-indicator online"></span>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success btn-sm btn-block">New Project
                    <i class="mdi mdi-plus"></i>
                </button>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#dashboard-dropdown" aria-expanded="false" aria-controls="dashboard-dropdown">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">Dashboard</span>
                <i class="menu-arrow"></i>
            </a>
        </li>
    </ul>
</nav>