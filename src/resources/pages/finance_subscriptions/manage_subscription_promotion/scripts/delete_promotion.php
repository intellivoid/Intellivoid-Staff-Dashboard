<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription\Feature;
    use IntellivoidAccounts\Objects\SubscriptionPromotion;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'delete')
        {
            try
            {
                delete_promotion();
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '101')
                ));
            }
        }
    }

    function delete_promotion()
    {
        /** @var SubscriptionPromotion $SubscriptionPromotion */
        $SubscriptionPromotion = DynamicalWeb::getMemoryObject('subscription_promotion');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        try
        {
            $IntellivoidAccounts->getSubscriptionPromotionManager()->deleteSubscriptionPromotion($SubscriptionPromotion);
        }
        catch (Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_promotion',
                array('id' => $_GET['id'], 'callback' => '101')
            ));
        }

        Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/subscription_promotions',
            array('callback' => '106')
        ));

    }