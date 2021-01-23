<?PHP

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
    use IntellivoidAccounts\Abstracts\ApplicationFlags;
    use IntellivoidAccounts\Abstracts\ApplicationStatus;
    use IntellivoidAccounts\Abstracts\AuthenticationMode;
    use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
    use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/applications'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $Application = $IntellivoidAccounts->getApplicationManager()->getApplication(
            ApplicationSearchMethod::byId, $_GET['id']
        );
    }
    catch (ApplicationNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/applications', array('callback' => '104')));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute('cloud/applications', array('callback' => '105')));
    }

    DynamicalWeb::setMemoryObject('application', $Application);
    DynamicalWeb::setMemoryObject('intellivoid_accounts', $IntellivoidAccounts);

    HTML::importScript('update_secret_key');
    HTML::importScript('update_logo');
    HTML::importScript('create_subscription_plan');
    HTML::importScript('update_permissions');
    HTML::importScript('delete_application');
    HTML::importScript('disable_application');
    HTML::importScript('enable_application');
    HTML::importScript('suspend_application');
    HTML::importScript('lift_suspension');
    HTML::importScript('update_authentication_mode');
    HTML::importScript('update_verification_status');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Manage <?PHP HTML::print($Application->Name); ?></title>
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
                            <div class="col-md-4 align-items-stretch">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin">
                                        <div class="card">
                                            <div class="card-body">
                                                <form action="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'update_logo'), true); ?>" method="POST" enctype="multipart/form-data">
                                                    <div class="d-flex align-items-start pb-3 border-bottom">
                                                        <img class="img-md" src="<?PHP HTML::print(getApplicationUrl($Application->PublicAppId, 'tiny')); ?>" alt="brand logo">

                                                        <div class="wrapper pl-4">
                                                            <p class="font-weight-bold mb-0">
                                                                <?PHP HTML::print($Application->Name); ?>
                                                                <?PHP
                                                                    if(in_array(ApplicationFlags::Official, $Application->Flags))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-verified text-primary pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is an official Intellivoid Application/Service\"></i>", false);

                                                                    }
                                                                    elseif(in_array(ApplicationFlags::Verified, $Application->Flags))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-verified text-success pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is verified & trusted\"></i>", false);
                                                                    }
                                                                    elseif(in_array(ApplicationFlags::Untrusted, $Application->Flags))
                                                                    {
                                                                        HTML::print("<i class=\"mdi mdi-alert text-danger pl-1\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"This is untrusted and unsafe\"></i>", false);
                                                                    }
                                                                ?>
                                                            </p>
                                                            <label class="btn btn-outline-primary btn-xs mt-2" for="file-selector" onchange="this.form.submit();">
                                                                <input id="file-selector" name="user_av_file" type="file" class="d-none">
                                                                Change Logo
                                                            </label>
                                                        </div>
                                                    </div>
                                                </form>
                                                <?PHP
                                                    switch($Application->Status)
                                                    {
                                                        case ApplicationStatus::Active:
                                                            ?>
                                                                <button class="btn btn-block btn-danger mt-3" onclick="location.href='<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'disable_application'), true); ?>'">Disable Application</button>
                                                            <?PHP
                                                            break;

                                                        case ApplicationStatus::Disabled:
                                                            ?>
                                                                <button class="btn btn-block btn-success mt-3" onclick="location.href='<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'enable_application'), true); ?>'">Enable Application</button>
                                                            <?PHP
                                                            break;

                                                        case ApplicationStatus::Suspended:
                                                            ?>
                                                                <button class="btn btn-block btn-warning disabled mt-3" disabled>Suspended</button>
                                                            <?PHP
                                                            break;
                                                    }
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Verification Status</h4>
                                                <form method="POST" action="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'update_verification_status'), true); ?>">
                                                    <div class="form-radio form-radio-flat">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="verification_status" id="verification_status" value="none"<?PHP if(count($Application->Flags) == 0){ HTML::print(" checked"); } ?>> None
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>

                                                    <div class="form-radio form-radio-flat">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="verification_status" id="verification_status" value="verified"<?PHP if($Application->has_flag(ApplicationFlags::Verified)){ HTML::print(" checked"); } ?>> Verified
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>

                                                    <div class="form-radio form-radio-flat">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="verification_status" id="verification_status" value="official"<?PHP if($Application->has_flag(ApplicationFlags::Official)){ HTML::print(" checked"); } ?>> Official
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>

                                                    <div class="form-radio form-radio-flat">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="verification_status" id="verification_status" value="untrusted"<?PHP if($Application->has_flag(ApplicationFlags::Untrusted)){ HTML::print(" checked"); } ?>> Untrusted
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>

                                                    <div class="form-group mt-4 mb-0">
                                                        <input type="submit" class="btn btn-block btn-outline-primary" value="Update">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Administrate</h4>
                                                <?PHP
                                                    if($Application->Status == ApplicationStatus::Suspended)
                                                    {
                                                        ?>
                                                        <button type="button" class="btn btn-success btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'lift_suspension'), true); ?>';">Lift Suspension</button>
                                                        <?PHP
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <button type="button" class="btn btn-warning btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'suspend_application'), true); ?>';">Suspend</button>
                                                        <?PHP
                                                    }
                                                ?>
                                                <button type="button" class="btn btn-danger btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'delete_application'), true); ?>';">Delete</button>
                                                <button type="button" class="btn btn-outline-primary btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('coa/authentication_requests', array('filter'=>'application_id', 'value'=>$Application->ID), true); ?>';">View Request Tokens</button>
                                                <button type="button" class="btn btn-outline-primary btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('coa/authentication_access', array('filter'=>'application_id', 'value'=>$Application->ID), true); ?>';">View Access Tokens</button>
                                                <button type="button" class="btn btn-outline-primary btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('coa/application_access', array('filter'=>'application_id', 'value'=>$Application->ID), true); ?>';">View Application Access</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Subscriptions</h4>
                                                <button type="button" class="btn btn-outline-success btn-xs btn-block" data-toggle="modal" data-target="#createSubscriptionPlanDialog">
                                                    <i class="mdi mdi-plus-circle"></i> Create Subscription Plan
                                                </button>
                                                <button type="button" class="btn btn-outline-info btn-xs btn-block" onclick="location.href='<?PHP DynamicalWeb::getRoute('finance_subscriptions/subscription_plans', array('filter'=>'application_id','value'=>$Application->ID), true); ?>';">
                                                    <i class="mdi mdi-magnify"></i> View Subscription Plans
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-8 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-muted">Details</h4>
                                        <div class="form-group pb-3">
                                            <label for="app_id">Internal Database ID</label>
                                            <input type="text" class="form-control" id="app_id" value="<?PHP HTML::print($Application->ID); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="app_name">Application Name</label>
                                            <input type="text" class="form-control" id="app_name" value="<?PHP HTML::print($Application->Name); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="app_name_safe">Application Name Safe</label>
                                            <input type="text" class="form-control" id="app_name_safe" value="<?PHP HTML::print($Application->NameSafe); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="border-bottom mt-4"></div>

                                    </div>

                                    <div class="card-body">
                                        <h4 class="card-title text-muted">Application Keys</h4>
                                        <form class="border-bottom" method="POST" action="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'update_secret_key'), true) ?>">
                                            <div class="form-group pb-3">
                                                <label for="public_app_id">Public Application ID</label>
                                                <input type="text" class="form-control" id="public_app_id" data-toggle="tooltip" data-placement="bottom" title="This is used for getting the public Application Logo and information" value="<?PHP HTML::print($Application->PublicAppId); ?>" aria-readonly="true" readonly>
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="app_secret_key">Secret Key</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="app_secret_key" data-toggle="tooltip" data-placement="bottom" title="This is for creating authentication requests, don't share it!" value="<?PHP HTML::print($Application->SecretKey); ?>" aria-readonly="true" readonly>
                                                    <div class="input-group-append">
                                                        <button class="input-group-btn btn btn-outline-dark text-white">
                                                            <i class="mdi mdi-reload"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-body">
                                        <h4 class="card-title text-muted">Settings</h4>
                                        <form action="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'update_authentication_mode'), true); ?>" method="POST">
                                            <div class="form-group">
                                                <label for="authentication_type">Authentication Type</label>
                                                <select class="form-control" name="authentication_type" id="authentication_type" onchange="this.form.submit();">
                                                    <option value="redirect"<?PHP if($Application->AuthenticationMode == AuthenticationMode::Redirect){ HTML::print(" selected", false); } ?>>Redirect</option>
                                                    <option value="placeholder"<?PHP if($Application->AuthenticationMode == AuthenticationMode::ApplicationPlaceholder){ HTML::print(" selected", false); } ?>>Application Placeholder</option>
                                                    <option value="code"<?PHP if($Application->AuthenticationMode == AuthenticationMode::Code){ HTML::print(" selected", false); } ?>>Code</option>
                                                </select>
                                            </div>
                                        </form>
                                        <form class="form-group pt-2" id="permissions-form" action="<?PHP DynamicalWeb::getRoute('cloud/manage_application', array('id' => $_GET['id'], 'action' => 'update_permissions'), true); ?>" method="POST">
                                            <label>Permissions</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="perm_view_personal_information" class="form-check-input"<?PHP if(in_array(AccountRequestPermissions::ReadPersonalInformation, $Application->Permissions)){HTML::print(' checked'); } ?>> View Personal Information
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>
                                                    <p class="text-muted text-small">Access to Personal Information like name, birthday and email</p>

                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="perm_make_purchases" id="perm_make_purchases" class="form-check-input"<?PHP if(in_array(AccountRequestPermissions::MakePurchases, $Application->Permissions)){HTML::print(' checked'); } ?>>  Make purchases
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>
                                                    <p class="text-muted text-small">Make purchases or activate paid subscriptions on users behalf</p>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="perm_view_email_address" id="perm_view_email_address" class="form-check-input"<?PHP if($Application->has_permission(AccountRequestPermissions::ViewEmailAddress)){HTML::print(' checked'); } ?>> View Email Address
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>
                                                    <p class="text-muted text-small">View the users Email Address</p>

                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="perm_telegram_notifications" id="perm_telegram_notifications" class="form-check-input"<?PHP if(in_array(AccountRequestPermissions::TelegramNotifications, $Application->Permissions)){HTML::print(' checked'); } ?>> Telegram Notifications
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>
                                                    <p class="text-muted text-small">Send notifications via Telegram (if available)</p>
                                                </div>
                                            </div>

                                        </form>


                                    </div>
                                    <div class="card-footer">
                                        <div class="row align-items-center">
                                            <button class="btn btn-success ml-auto mr-2" onclick="$('#permissions-form').submit();">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
                <?PHP HTML::importScript('create_subscription_plan_dialog'); ?>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>