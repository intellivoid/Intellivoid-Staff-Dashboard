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
use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPlanSearchMethod;
use IntellivoidAccounts\Abstracts\SubscriptionPlanStatus;
use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
use IntellivoidAccounts\Exceptions\SubscriptionPlanNotFoundException;
use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    function get_location()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
        $server = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
        return $protocol.$server.$port;
    }

    if(isset($_GET['id']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('applications'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $SubscriptionPlan = $IntellivoidAccounts->getSubscriptionPlanManager()->getSubscriptionPlan(
            SubscriptionPlanSearchMethod::byId, $_GET['id']
        );
    }
    catch (SubscriptionPlanNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
                'subscription_plans', array('callback' => '104')
        ));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
            'subscription_plans', array('callback' => '105')
        ));
    }

    $ApplicationExists = false;
    DynamicalWeb::setBoolean('application_exists', false);


    try
    {
        $Application = $IntellivoidAccounts->getApplicationManager()->getApplication(
            ApplicationSearchMethod::byId, $SubscriptionPlan->ApplicationID
        );

        $ApplicationExists = true;
        DynamicalWeb::setBoolean('application_exists', true);
        DynamicalWeb::setMemoryObject('application', $Application);
    }
    catch(Exception $e)
    {
        $ApplicationExists = false;
        DynamicalWeb::setBoolean('application_exists', false);
    }

    DynamicalWeb::setMemoryObject('subscription_plan', $SubscriptionPlan);
    DynamicalWeb::setMemoryObject('intellivoid_accounts', $IntellivoidAccounts);

    HTML::importScript('update_status');
    HTML::importScript('update_properties');
    HTML::importScript('create_promotion');

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
                                                <div class="d-flex align-items-start pb-3 border-bottom">
                                                    <?PHP
                                                    if($ApplicationExists)
                                                    {
                                                        ?>
                                                        <img class="img-md" src="<?PHP HTML::print(getApplicationUrl($Application->PublicAppId, 'tiny')); ?>" alt="brand logo">
                                                        <?PHP
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <i class="mdi mdi-information icon-lg"></i>
                                                        <?PHP
                                                    }
                                                    ?>

                                                    <div class="wrapper pl-4">
                                                        <p class="font-weight-bold mb-0">
                                                            <?PHP
                                                            if($ApplicationExists)
                                                            {
                                                                HTML::print($Application->Name);
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
                                                            }
                                                            else
                                                            {
                                                                HTML::print("Application not Found");
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <button class="btn btn-block btn-outline-primary mt-3" onclick="location.href='<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $Application->ID), true); ?>'">Manage Application</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Update Status</h4>
                                                <form method="POST" action="<?PHP DynamicalWeb::getRoute('manage_subscription_plan', array('id' => $_GET['id'], 'action' => 'update_status'), true); ?>">
                                                    <div class="form-radio form-radio-flat">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="status" id="available_status" value="available"<?PHP if($SubscriptionPlan->Status == SubscriptionPlanStatus::Available){ HTML::print(" checked"); } ?>> Available
                                                            <i class="input-helper"></i>
                                                        </label>
                                                    </div>

                                                    <div class="form-radio form-radio-flat">
                                                        <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="status" id="unavailable_status" value="unavailable"<?PHP if($SubscriptionPlan->Status == SubscriptionPlanStatus::Unavailable){ HTML::print(" checked"); } ?>> Unavailable
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
                                                <h4 class="card-title">Subscriptions</h4>
                                                <button type="button" class="btn btn-outline-primary btn-xs btn-block" data-toggle="modal" data-target="#createPromotionDialog">
                                                    <i class="mdi mdi-plus-circle"></i> Create Promotion
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
                                            <label for="internal_id">Internal Database ID</label>
                                            <input type="text" class="form-control" id="internal_id" value="<?PHP HTML::print($SubscriptionPlan->ID); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="public_id">Public ID</label>
                                            <input type="text" class="form-control" id="public_id" value="<?PHP HTML::print($SubscriptionPlan->PublicID); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="plan_name">Plan Name</label>
                                            <input type="text" class="form-control" id="plan_name" value="<?PHP HTML::print($SubscriptionPlan->PlanName); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="status">Status</label>
                                            <input type="text" class="form-control" id="status" value="<?PHP HTML::print($SubscriptionPlan->Status); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="application_id">
                                                Application ID
                                                <a href="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $SubscriptionPlan->ApplicationID), true); ?>" class="text-white">
                                                    <i class="mdi mdi-database-search"></i>
                                                </a>
                                            </label>
                                            <input type="text" class="form-control" id="application_id" value="<?PHP HTML::print($SubscriptionPlan->ApplicationID); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="last_updated_timestamp">Last Updated Unix Timestamp</label>
                                            <input type="text" class="form-control" id="last_updated_timestamp" value="<?PHP HTML::print($SubscriptionPlan->LastUpdated); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="created_timestamp">Created Unix Timestamp</label>
                                            <input type="text" class="form-control" id="created_timestamp" value="<?PHP HTML::print($SubscriptionPlan->CreatedTimestamp); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="border-bottom mt-4"></div>
                                    </div>

                                    <div class="card-body">
                                        <form method="POST" id="details-form" name="details-form" action="<?PHP DynamicalWeb::getRoute('manage_subscription_plan', array('id' => $_GET['id'], 'action' => 'update_properties'), true); ?>">
                                            <h4 class="card-title text-muted">Properties</h4>
                                            <div class="form-group pb-3">
                                                <label for="initial_price">Initial Price (USD)</label>
                                                <input type="text" class="form-control" name="initial_price" id="initial_price" value="<?PHP HTML::print($SubscriptionPlan->InitialPrice); ?>">
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="cycle_price">Cycle Price (USD)</label>
                                                <input type="text" class="form-control" name="cycle_price" id="cycle_price" value="<?PHP HTML::print($SubscriptionPlan->CyclePrice); ?>">
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="billing_cycle">Unix Timestamp Billing Cycle</label>
                                                <input type="text" class="form-control" name="billing_cycle" id="billing_cycle" value="<?PHP HTML::print($SubscriptionPlan->BillingCycle); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="features">Features Structure (JSON)</label>
                                                <textarea class="form-control" id="features" name="features" rows="10"><?PHP HTML::print(json_encode($SubscriptionPlan->Features, JSON_PRETTY_PRINT)); ?></textarea>
                                            </div>
                                            <p class="mt-2">
                                                Enter <code>python3 <(curl "<?PHP HTML::print(get_location() . '/?action=stream&blob=feature_builder'); ?>" -s -N)</code> into your terminal
                                                to generate valid JSON data
                                            </p>
                                        </form>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row align-items-center">
                                            <button class="btn btn-success ml-auto mr-2" onclick="$('#details-form').submit();">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?PHP HTML::importScript('create_promotion_dialog'); ?>
                    </div>
                    <?PHP HTML::importSection('footer'); ?>
                </div>
            </div>
        </div>
        <?PHP HTML::importSection('js_scripts'); ?>
        <script src="/assets/js/shared/tooltips.js"></script>
    </body>
</html>