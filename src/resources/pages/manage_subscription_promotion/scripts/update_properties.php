<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\Subscription\Feature;
    use IntellivoidAccounts\Objects\SubscriptionPromotion;

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
                    Actions::redirect(DynamicalWeb::getRoute('manage_subscription_plan',
                        array('id' => $_GET['id'], 'callback' => '101')
                    ));
                }
            }
        }
    }

    function update_properties()
    {
        /** @var SubscriptionPromotion $SubscriptionPromotion */
        $SubscriptionPromotion = DynamicalWeb::getMemoryObject('subscription_promotion');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        if(isset($_POST['initial_price']))
        {
            $InitialPrice = (float)$_POST['initial_price'];

            if($InitialPrice < 0)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                    array('id' => $_GET['id'], 'callback' => '103')
                ));
            }

            $SubscriptionPromotion->InitialPrice = $InitialPrice;
        }

        if(isset($_POST['cycle_price']))
        {
            $CyclePrice = (float)$_POST['cycle_price'];

            if($CyclePrice < 0)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                    array('id' => $_GET['id'], 'callback' => '104')
                ));
            }

            $SubscriptionPromotion->CyclePrice = $CyclePrice;
        }

        if(isset($_POST['affiliation_account_id']))
        {
            $AffiliationAccountID = (int)$_POST['affiliation_account_id'];
            $SubscriptionPromotion->AffiliationAccountID = $AffiliationAccountID;
            // TODO: Add validation for Account ID
        }

        if(isset($_POST['affiliation_initial_share']))
        {
            if($SubscriptionPromotion->AffiliationAccountID > 0)
            {
                $AffiliationInitialShare = (float)$_POST['affiliation_initial_share'];

                if($AffiliationInitialShare < 0)
                {
                    Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                        array('id' => $_GET['id'], 'callback' => '111')
                    ));
                }

                $SubscriptionPromotion->AffiliationInitialShare = $AffiliationInitialShare;
            }
            else
            {
                $SubscriptionPromotion->AffiliationInitialShare = (float)0;
            }
        }

        if(isset($_POST['affiliation_cycle_share']))
        {
            if($SubscriptionPromotion->AffiliationAccountID > 0)
            {
                $AffiliationCycleShare = (float)$_POST['affiliation_cycle_share'];

                if($AffiliationCycleShare < 0)
                {
                    Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                        array('id' => $_GET['id'], 'callback' => '112')
                    ));
                }

                $SubscriptionPromotion->AffiliationCycleShare = $AffiliationCycleShare;
            }
            else
            {
                $SubscriptionPromotion->AffiliationCycleShare = (float)0;
            }
        }


        if($_POST['features'])
        {
            $Features = json_decode($_POST['features'], true);

            if($Features == null)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                    array('id' => $_GET['id'], 'callback' => '106')
                ));
            }

            $FeaturesArray = [];

            if(count($Features) > 0)
            {

                foreach($Features as $Feature)
                {
                    if(isset($Feature['Name']))
                    {
                        $Feature['name'] = $Feature['Name'];
                    }

                    if(isset($Feature['Value']))
                    {
                        $Feature['value'] = $Feature['Value'];
                    }

                    $FeatureObject = Feature::fromArray($Feature);

                    if(is_null($FeatureObject->Name))
                    {
                        Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                            array('id' => $_GET['id'], 'callback' => '107')
                        ));
                    }

                    if(is_null($FeatureObject->Value))
                    {
                        Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                            array('id' => $_GET['id'], 'callback' => '107')
                        ));
                    }

                    $FeaturesArray[] = $FeatureObject;
                }
            }

            $SubscriptionPromotion->Features = $FeaturesArray;
        }

        $SubscriptionPromotion->LastUpdatedTimestamp = (int)time();

        try
        {
            $IntellivoidAccounts->getSubscriptionPromotionManager()->updateSubscriptionPromotion($SubscriptionPromotion);
        }
        catch (Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
                array('id' => $_GET['id'], 'callback' => '101')
            ));
        }

        Actions::redirect(DynamicalWeb::getRoute('manage_subscription_promotion',
            array('id' => $_GET['id'], 'callback' => '108')
        ));

    }