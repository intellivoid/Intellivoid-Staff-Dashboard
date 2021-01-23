<?PHP
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
?>
<div class="modal fade" id="createPromotionDialog" tabindex="-1" role="dialog" aria-labelledby="createPromotionDialogLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?PHP DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan', array('id' => $_GET['id'], 'action' => 'create_promotion'), true); ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Create Subscription Promotion</h5>
                    <button type="button" class="close" id="createPromotionDialogLabel" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <i class="mdi mdi-close"></i>
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="from-group">
                        <label for="promotion_code">Promotion Code</label>
                        <input class="form-control" type="text" name="promotion_code" id="promotion_code" placeholder="Promotion Code (PROMO123, TOPS5, KFC, etc...)" required>
                    </div>
                    <div class="from-group mt-3">
                        <label for="initial_price">Initial Price (USD)</label>
                        <input class="form-control" type="text" name="initial_price" id="initial_price" placeholder="2.99" required>
                    </div>
                    <div class="from-group mt-3">
                        <label for="cycle_price">Cycle Price (USD)</label>
                        <input class="form-control" type="text" name="cycle_price" id="cycle_price" placeholder="1.99" required>
                    </div>
                    <div class="border-bottom mt-4"></div>
                    <div class="from-group mt-3">
                        <label for="affiliation_account_id">Affiliation Account ID</label>
                        <input class="form-control" type="text" name="affiliation_account_id" id="affiliation_account_id" placeholder="Leave 0 for none" value="0" required>
                    </div>
                    <p class="mt-3 text-muted">
                        If the share is greater than the plan's pricing then the affiliate will get what the plan's pricing is instead of the specified share.
                        If the account account ID is set to 0 then these values would be ignored
                    </p>
                    <div class="from-group mt-3">
                        <label for="initial_share">Initial Price Share</label>
                        <input class="form-control" type="text" name="initial_share" id="initial_share" placeholder="Leave 0 for none" value="0">
                    </div>
                    <div class="from-group mt-3">
                        <label for="cycle_share">Cycle Price Share</label>
                        <input class="form-control" type="text" name="cycle_share" id="cycle_share" placeholder="Leave 0 for none" value="0">
                    </div>
                    <div class="border-bottom mt-4"></div>
                    <p class="mt-3 text-muted">
                        <i class="mdi mdi-alert text-warning"></i> The promotion features will override/add the existing features to the subscription plan
                    </p>
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
                    <input type="submit" class="btn btn-primary" value="Create Promotion">
                </div>
            </form>

        </div>
    </div>
</div>