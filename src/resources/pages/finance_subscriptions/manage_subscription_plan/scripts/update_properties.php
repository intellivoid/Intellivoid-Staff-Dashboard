<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\IntellivoidAccounts;
use IntellivoidAccounts\Objects\Subscription\Feature;
use IntellivoidAccounts\Objects\SubscriptionPlan;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_properties')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    update_properties();
                }
                catch(Exception $e)
                {
                    Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                        array('id' => $_GET['id'], 'callback' => '101')
                    ));
                }
            }
        }
    }

    function update_properties()
    {
        /** @var SubscriptionPlan $SubscriptionPlan */
        $SubscriptionPlan = DynamicalWeb::getMemoryObject('subscription_plan');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        if(isset($_POST['initial_price']))
        {
            $InitialPrice = (float)$_POST['initial_price'];

            if($InitialPrice < 0)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '103')
                ));
            }

            $SubscriptionPlan->InitialPrice = $InitialPrice;
        }

        if(isset($_POST['cycle_price']))
        {
            $CyclePrice = (float)$_POST['cycle_price'];

            if($CyclePrice < 0)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '104')
                ));
            }

            $SubscriptionPlan->CyclePrice = $CyclePrice;
        }

        if(isset($_POST['billing_cycle']))
        {
            $BillingCycle = (int)$_POST['billing_cycle'];

            if($BillingCycle < 1)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '105')
                ));
            }

            $SubscriptionPlan->BillingCycle = $BillingCycle;
        }

        if($_POST['features'])
        {
            $Features = json_decode($_POST['features'], true);

            if($Features == null)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '106')
                ));
            }

            $FeaturesArray = [];

            if(count($Features) > 0)
            {

                foreach($Features as $Feature)
                {
                    $Feature['name'] = $Feature['Name'];
                    $Feature['value'] = $Feature['Value'];
                    $FeatureObject = Feature::fromArray($Feature);

                    if(is_null($FeatureObject->Name))
                    {
                        Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                            array('id' => $_GET['id'], 'callback' => '107')
                        ));
                    }

                    if(is_null($FeatureObject->Value))
                    {
                        Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                            array('id' => $_GET['id'], 'callback' => '107')
                        ));
                    }

                    $FeaturesArray[] = $FeatureObject;
                }
            }

            $SubscriptionPlan->Features = $FeaturesArray;
        }

        $SubscriptionPlan->LastUpdated = (int)time();

        try
        {
            $IntellivoidAccounts->getSubscriptionPlanManager()->updateSubscriptionPlan($SubscriptionPlan);
        }
        catch (Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '101')
            ));
        }

        Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
            array('id' => $_GET['id'], 'callback' => '108')
        ));

    }