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
use IntellivoidAccounts\Abstracts\SearchMethods\SubscriptionPromotionSearchMethod;
use IntellivoidAccounts\Abstracts\SubscriptionPlanStatus;
use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
use IntellivoidAccounts\Exceptions\SubscriptionPlanNotFoundException;
use IntellivoidAccounts\Exceptions\SubscriptionPromotionNotFoundException;
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
        Actions::redirect(DynamicalWeb::getRoute('subscription_promotions'));
    }

    $IntellivoidAccounts = new IntellivoidAccounts();

    try
    {
        $SubscriptionPromotion = $IntellivoidAccounts->getSubscriptionPromotionManager()->getSubscriptionPromotion(
                SubscriptionPromotionSearchMethod::byId, $_GET['id']
        );

    }
    catch (SubscriptionPromotionNotFoundException $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
                'subscription_promotions', array('callback' => '104')
        ));
    }
    catch(Exception $e)
    {
        Actions::redirect(DynamicalWeb::getRoute(
            'subscription_promotions', array('callback' => '105')
        ));
    }


    DynamicalWeb::setMemoryObject('subscription_promotion', $SubscriptionPromotion);
    DynamicalWeb::setMemoryObject('intellivoid_accounts', $IntellivoidAccounts);

    HTML::importScript('update_properties');
    HTML::importScript('delete_promotion');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>Intellivoid Staff - Manage Promotion <?PHP HTML::print($SubscriptionPromotion->PromotionCode); ?></title>
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

                            <div class="col-md-6 grid-margin">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-muted">Details</h4>
                                        <div class="form-group pb-3">
                                            <label for="internal_id">Internal Database ID</label>
                                            <input type="text" class="form-control" id="internal_id" value="<?PHP HTML::print($SubscriptionPromotion->ID); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="public_id">Public ID</label>
                                            <input type="text" class="form-control" id="public_id" value="<?PHP HTML::print($SubscriptionPromotion->PublicID); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="promotion_code">Promotion Code</label>
                                            <input type="text" class="form-control" id="promotion_code" value="<?PHP HTML::print($SubscriptionPromotion->PromotionCode); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="subscription_plan_id">
                                                Subscription Plan ID
                                                <a href="<?PHP DynamicalWeb::getRoute('manage_subscription_plan', array('id' => $SubscriptionPromotion->SubscriptionPlanID), true); ?>" class="text-white">
                                                    <i class="mdi mdi-database-search"></i>
                                                </a>
                                            </label>
                                            <input type="text" class="form-control" id="subscription_plan_id" value="<?PHP HTML::print($SubscriptionPromotion->SubscriptionPlanID); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="last_updated_timestamp">Last Updated Unix Timestamp</label>
                                            <input type="text" class="form-control" id="last_updated_timestamp" value="<?PHP HTML::print($SubscriptionPromotion->LastUpdatedTimestamp); ?>" aria-readonly="true" readonly>
                                        </div>
                                        <div class="form-group pb-3">
                                            <label for="created_timestamp">Created Unix Timestamp</label>
                                            <input type="text" class="form-control" id="created_timestamp" value="<?PHP HTML::print($SubscriptionPromotion->CreatedTimestamp); ?>" aria-readonly="true" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 grid-margin">
                                <div class="card">
                                    <div class="card-body">
                                        <form method="POST" id="details-form" name="details-form" action="<?PHP DynamicalWeb::getRoute('manage_subscription_promotion', array('id' => $_GET['id'], 'action' => 'update_properties'), true); ?>">
                                            <h4 class="card-title text-muted">Properties</h4>
                                            <div class="form-group pb-3">
                                                <label for="initial_price">Initial Price (USD)</label>
                                                <input type="text" class="form-control" name="initial_price" id="initial_price" value="<?PHP HTML::print($SubscriptionPromotion->InitialPrice); ?>" required>
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="cycle_price">Cycle Price (USD)</label>
                                                <input type="text" class="form-control" name="cycle_price" id="cycle_price" value="<?PHP HTML::print($SubscriptionPromotion->CyclePrice); ?>" required>
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="affiliation_account_id">
                                                    Affiliation Account ID
                                                    <a href="<?PHP DynamicalWeb::getRoute('manage_account', array('id' => $SubscriptionPromotion->AffiliationAccountID), true); ?>" class="text-white">
                                                        <i class="mdi mdi-database-search"></i>
                                                    </a>
                                                </label>
                                                <input type="text" class="form-control" name="affiliation_account_id" id="affiliation_account_id" value="<?PHP HTML::print($SubscriptionPromotion->AffiliationAccountID); ?>" required>
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="affiliation_initial_share">Affiliation Initial Share (USD)</label>
                                                <input type="text" class="form-control" name="affiliation_initial_share" id="affiliation_initial_share" value="<?PHP HTML::print($SubscriptionPromotion->AffiliationInitialShare); ?>">
                                            </div>
                                            <div class="form-group pb-3">
                                                <label for="affiliation_cycle_share">Affiliation Cycle Share (USD)</label>
                                                <input type="text" class="form-control" name="affiliation_cycle_share" id="affiliation_cycle_share" value="<?PHP HTML::print($SubscriptionPromotion->AffiliationCycleShare); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="features">Features Structure (JSON)</label>
                                                <textarea class="form-control" id="features" name="features" rows="10"><?PHP HTML::print(json_encode($SubscriptionPromotion->Features, JSON_PRETTY_PRINT)); ?></textarea>
                                            </div>
                                            <p class="mt-2">
                                                Enter <code>python3 <(curl "<?PHP HTML::print(get_location() . '/?action=stream&blob=feature_builder'); ?>" -s -N)</code> into your terminal
                                                to generate valid JSON data
                                            </p>
                                        </form>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row align-items-center">
                                            <button class="btn btn-danger ml-auto mr-2" onclick="location.href='<?PHP DynamicalWeb::getRoute('manage_subscription_promotion', array('id' => $_GET['id'], 'action' => 'delete'), true); ?>';">Delete</button>
                                            <button class="btn btn-success mr-2" onclick="$('#details-form').submit();">Save Changes</button>
                                        </div>
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