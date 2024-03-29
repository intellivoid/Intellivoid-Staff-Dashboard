<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAccounts\Objects\UserAgentRecord;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/accounts'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $Account = $IntellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, (int)$_GET['id']);
    }
    catch (AccountNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/accounts', array('callback' => '104')));
    }
    catch (Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/accounts', array('callback' => '105')));
    }

    if($Account->PersonalInformation->FirstName == null)
    {
        define("USER_FIRST_NAME", "", false);
    }
    else
    {
        define("USER_FIRST_NAME", "value=\"" . htmlspecialchars($Account->PersonalInformation->FirstName, ENT_QUOTES, 'UTF-8') . "\"", false);
    }

    if($Account->PersonalInformation->LastName == null)
    {
        define("USER_LAST_NAME", "", false);
    }
    else
    {
        define("USER_LAST_NAME", "value=\"" . htmlspecialchars($Account->PersonalInformation->LastName, ENT_QUOTES, 'UTF-8') . "\"", false);
    }

    if($Account->PersonalInformation->BirthDate->Year == 0)
    {
        define("USER_BOD_YEAR", "", false);
    }
    else
    {
        define("USER_BOD_YEAR", $Account->PersonalInformation->BirthDate->Year, false);
    }

    if($Account->PersonalInformation->BirthDate->Month == 0)
    {
        define("USER_BOD_MONTH", "", false);
    }
    else
    {
        define("USER_BOD_MONTH", $Account->PersonalInformation->BirthDate->Month, false);
    }

    if($Account->PersonalInformation->BirthDate->Day == 0)
    {
        define("USER_BOD_DAY", "", false);
    }
    else
    {
        define("USER_BOD_DAY", $Account->PersonalInformation->BirthDate->Day, false);
    }

    // Actions
    HTML::importScript('update_account');
    HTML::importScript('export_data');
    HTML::importScript('apply_permission');
    HTML::importScript('send_notification');
    HTML::importScript('set_status');
    HTML::importScript('revoke_permission');
    HTML::importScript('revoke_access');
    HTML::importScript('disable_application');
    HTML::importScript('enable_application');

    // Visual Components
    HTML::importScript('render_known_hosts');
    HTML::importScript('render_known_devices');
    HTML::importScript('render_details');
    HTML::importScript('render_coa_access');
    HTML::importScript('render_applications');
    HTML::importScript('render_login_history');
    HTML::importScript('render_roles');
    HTML::importScript('telegram_details');
    HTML::importScript('render_status');
    HTML::importScript('render_audit_logs');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <link rel="stylesheet" href="/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css" />
        <title>Intellivoid Staff - Manage @<?PHP HTML::print($Account->Username); ?></title>
    </head>
    <body class="dark-theme sidebar-dark">
        <div class="container-scroller">
            <?PHP HTML::importSection('navigation'); ?>
            <div class="container-fluid page-body-wrapper">
                <?PHP HTML::importSection('sidebar'); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row profile-page">
                            <div class="col-12">
                                <?PHP HTML::importScript('callbacks'); ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="profile-header text-white">
                                            <div class="d-flex justify-content-around">
                                                <div class="profile-info d-flex align-items-center">
                                                    <img class="rounded-circle img-lg" src="<?PHP HTML::print(getAvatarUrl($Account->PublicID, 'normal')); ?>" alt="profile image">
                                                    <div class="wrapper pl-4">
                                                        <p class="profile-user-name">
                                                            <?PHP HTML::print($Account->Username); ?>
                                                            <?PHP
                                                                if($Account->Configuration->Roles->has_role("ADMINISTRATOR"))
                                                                {
                                                                    HTML::print("<i class=\"mdi mdi-shield\"></i>", false);
                                                                }
                                                                if($Account->Configuration->Roles->has_role("MODERATOR"))
                                                                {
                                                                    HTML::print("<i class=\"mdi mdi-security\"></i>", false);
                                                                }
                                                                if($Account->Configuration->Roles->has_role("SUPPORT"))
                                                                {
                                                                    HTML::print("<i class=\"mdi mdi-lifebuoy\"></i>", false);
                                                                }
                                                            ?>
                                                        </p>
                                                        <div class="wrapper d-flex align-items-center">
                                                            <p class="profile-user-designation"><?PHP HTML::print(date("F j, Y, g:i a", $Account->CreationDate)); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="profile-body">
                                            <ul class="nav tab-switch" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="user-profile-info-tab" data-toggle="pill" href="#user-profile-info" role="tab" aria-controls="user-profile-info" aria-selected="true" style="border-bottom-width: 0;">Profile</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-profile-kh-tab" data-toggle="pill" href="#user-profile-kh" role="tab" aria-controls="user-profile-kh" aria-selected="false" style="border-bottom-width: 0;">Known Hosts</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-profile-kd-tab" data-toggle="pill" href="#user-profile-kd" role="tab" aria-controls="user-profile-kd" aria-selected="false" style="border-bottom-width: 0;">Devices</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-login-history-tab" data-toggle="pill" href="#user-login-history" role="tab" aria-controls="user-login-history" aria-selected="false" style="border-bottom-width: 0;">Login History</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-profile-coa-tab" data-toggle="pill" href="#user-coa-details" role="tab" aria-controls="user-coa-details" aria-selected="false" style="border-bottom-width: 0;">COA Access</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-applications-tab" data-toggle="pill" href="#user-applications" role="tab" aria-controls="user-applications" aria-selected="false" style="border-bottom-width: 0;">Applications</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-audit-tab" data-toggle="pill" href="#user-audit" role="tab" aria-controls="user-audit" aria-selected="false" style="border-bottom-width: 0;">Audit</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="user-profile-details-tab" data-toggle="pill" href="#user-profile-details" role="tab" aria-controls="user-profile-details" aria-selected="false" style="border-bottom-width: 0;">Details</a>
                                                </li>
                                            </ul>
                                            <div class="row">
                                                <div class="col-md-9 border-right">
                                                    <div class="tab-content tab-body" id="profile-log-switch">
                                                        <div class="tab-pane fade show active pr-3" id="user-profile-info" role="tabpanel" aria-labelledby="user-profile-info-tab">
                                                            <?PHP HTML::importScript('edit_personal_information'); ?>
                                                            <?PHP
                                                                if($Account->Configuration->VerificationMethods->TelegramClientLinked)
                                                                {
                                                                    render_telegram_details($IntellivoidAccounts, $Account);
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-profile-kh" role="tabpanel" aria-labelledby="user-profile-kh-tab">
                                                            <?PHP render_known_hosts($IntellivoidAccounts, $Account->Configuration->KnownHosts->KnownHosts, $Account); ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-login-history" role="tabpanel" aria-labelledby="user-login-history-tab">
                                                            <?PHP render_login_history($IntellivoidAccounts, $Account);  ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-profile-kd" role="tabpanel" aria-labelledby="user-profile-kd-tab">
                                                            <?PHP
                                                                $DeviceResults = array();
                                                                foreach($Account->Configuration->KnownHosts->KnownHosts as $host_id)
                                                                {
                                                                    $Results = $IntellivoidAccounts->getTrackingUserAgentManager()->getRecordsByHost($host_id);
                                                                    foreach($Results as $device)
                                                                    {
                                                                        $device = UserAgentRecord::fromArray($device);
                                                                        $DeviceResults[$device->ID] = $device;
                                                                    }
                                                                }
                                                                render_known_devices($IntellivoidAccounts, $DeviceResults);
                                                            ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-coa-details" role="tabpanel" aria-labelledby="user-coa-details-tab">
                                                            <?PHP render_coa_access($IntellivoidAccounts, $Account);  ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-applications" role="tabpanel" aria-labelledby="user-applications-tab">
                                                            <?PHP render_applications($IntellivoidAccounts, $Account);  ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-audit" role="tabpanel" aria-labelledby="user-audit-tab">
                                                            <?PHP render_audit_logs($IntellivoidAccounts, $Account);  ?>
                                                        </div>
                                                        <div class="tab-pane fade" id="user-profile-details" role="tabpanel" aria-labelledby="user-profile-details-tab">
                                                            <?PHP render_details($IntellivoidAccounts, $Account);  ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h5 class="my-4">Account Balance</h5>
                                                    <div class="wrapper mt-4">
                                                        <div class="mb-3">
                                                            <div class="d-flex align-items-center">
                                                                <h1 class="font-weight-medium mb-2">$<?PHP HTML::print($Account->Configuration->Balance); ?> USD</h1>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <p class="text-muted mb-0 ml-1">
                                                                    <a class="text-primary" href="<?PHP DynamicalWeb::getRoute('finance/transaction_records', array('filter' => 'account_id', 'value' => $Account->ID), true); ?>"><?PHP HTML::print("View transaction records"); ?></a>
                                                                </p>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <p class="text-muted mb-0 ml-1">
                                                                    <a class="text-primary" href="<?PHP DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_promotion', array('filter' => 'account_id', 'value' => $Account->ID), true); ?>"><?PHP HTML::print("View Active Subscriptions"); ?></a>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 border-top"></div>
                                                    <h5 class="my-4">Status</h5>
                                                    <?PHP render_status($Account); ?>
                                                    <div class="mt-4 border-top"></div>
                                                    <h5 class="my-4">Roles</h5>
                                                    <?PHP render_roles($Account); ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP HTML::importScript('gba_dialog'); ?>
                    <?PHP HTML::importScript('prm_dialog'); ?>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>