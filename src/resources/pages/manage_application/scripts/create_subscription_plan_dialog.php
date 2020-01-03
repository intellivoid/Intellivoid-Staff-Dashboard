<?PHP
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

    function get_location()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) ? 'https://' : 'http://';
        $server = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
        return $protocol.$server.$port;
    }
?>
<div class="modal fade" id="createSubscriptionPlanDialog" tabindex="-1" role="dialog" aria-labelledby="createSubscriptionPlanDialogLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?PHP DynamicalWeb::getRoute('manage_application', array('id' => $_GET['id'], 'action' => 'create_subscription'), true); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSubscriptionPlanDialogLabel">Create Subscription Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <i class="mdi mdi-close"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="from-group">
                        <label for="plan_name">Plan Name</label>
                        <input class="form-control" type="text" name="plan_name" id="plan_name" placeholder="Plan Name (Basic, Professional, Free, etc.)">
                    </div>
                    <div class="from-group mt-3">
                        <label for="initial_price">Initial Price (USD)</label>
                        <input class="form-control" type="text" name="initial_price" id="initial_price" placeholder="2.99">
                    </div>
                    <div class="from-group mt-3">
                        <label for="cycle_price">Cycle Price (USD)</label>
                        <input class="form-control" type="text" name="cycle_price" id="cycle_price" placeholder="1.99">
                    </div>
                    <div class="from-group mt-3">
                        <label for="unix_billing_cycle">Unix Billing Cycle</label>
                        <input class="form-control" type="text" name="unix_billing_cycle" id="unix_billing_cycle" placeholder="2628000" value="2628000">
                    </div>
                    <div class="form-group mt-3">
                        <label for="features">Features Structure (JSON)</label>
                        <textarea class="form-control" id="features" name="features" rows="4"></textarea>
                    </div>
                    <p class="mt-2">
                        Enter <code>python3 <(curl "<?PHP HTML::print(get_location() . '/?action=stream&blob=feature_builder'); ?>" -s -N)</code> into your terminal
                        to generate valid JSON data
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Create Plan">
                </div>
            </form>

        </div>
    </div>
</div>