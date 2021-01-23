<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
use IntellivoidAccounts\Exceptions\AccountNotFoundException;
use IntellivoidAccounts\Exceptions\InvalidBillingCycleException;
    use IntellivoidAccounts\Exceptions\InvalidCyclePriceException;
use IntellivoidAccounts\Exceptions\InvalidCyclePriceShareException;
use IntellivoidAccounts\Exceptions\InvalidFeatureException;
    use IntellivoidAccounts\Exceptions\InvalidInitialPriceException;
use IntellivoidAccounts\Exceptions\InvalidInitialPriceShareException;
use IntellivoidAccounts\Exceptions\InvalidSubscriptionPlanNameException;
use IntellivoidAccounts\Exceptions\InvalidSubscriptionPromotionNameException;
use IntellivoidAccounts\Exceptions\SubscriptionPlanAlreadyExistsException;
use IntellivoidAccounts\Exceptions\SubscriptionPromotionAlreadyExistsException;
use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use IntellivoidAccounts\Objects\Subscription\Feature;
use IntellivoidAccounts\Objects\SubscriptionPlan;
use IntellivoidAccounts\Utilities\Validate;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'create_promotion')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    create_promotion();
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

    function create_promotion()
    {

        if(isset($_POST['promotion_code']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        if(isset($_POST['initial_price']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        if(isset($_POST['cycle_price']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        if(isset($_POST['affiliation_account_id']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        if(isset($_POST['initial_share']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        if(isset($_POST['cycle_share']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        // Validate features
        $features = json_decode($_POST['features'], true);
        $features_array = array();
        if($features !== null)
        {
            foreach($features as $feature)
            {
                if(isset($feature['Name']))
                {
                    $feature['name'] = $feature['Name'];
                }

                if(isset($feature['Value']))
                {
                    $feature['value'] = $feature['Value'];
                }

                $FeatureObject = Feature::fromArray($feature);

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

                $features_array[] = $FeatureObject;
            }
        }


        if(Validate::subscriptionPromotionCode($_POST['promotion_code']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '109')
            ));
        }


        /** @var SubscriptionPlan $SubscriptionPlan */
        $SubscriptionPlan = DynamicalWeb::getMemoryObject('subscription_plan');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        // Get the values
        $InitialPrice = (float)$_POST['initial_price'];
        $CyclePrice = (float)$_POST['cycle_price'];
        $AffiliationAccountID = (int)$_POST['affiliation_account_id'];
        $InitialShare = (float)$_POST['initial_share'];
        $CycleShare = (float)$_POST['cycle_share'];

        if($AffiliationAccountID == 0)
        {
            $InitialShare = (float)0;
            $CycleShare = (float)0;
        }
        else
        {
            try
            {
                $IntellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $AffiliationAccountID);
            }
            catch (AccountNotFoundException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '110')
                ));
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '101')
                ));
            }

            if($InitialShare < 0)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '111')
                ));
            }

            if($CycleShare < 0)
            {
                Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '112')
                ));
            }
        }


        try
        {
            $IntellivoidAccounts->getSubscriptionPromotionManager()->createSubscriptionPromotion(
                $SubscriptionPlan->ID, $_POST['promotion_code'], $InitialPrice, $CyclePrice,
                $AffiliationAccountID, $InitialShare, $CycleShare, $features_array
            );
        }
        catch (AccountNotFoundException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '110')
            ));
        }
        catch (InvalidCyclePriceException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '104')
            ));
        }
        catch (InvalidCyclePriceShareException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '112')
            ));
        }
        catch (InvalidFeatureException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '107')
            ));
        }
        catch (InvalidInitialPriceException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '103')
            ));
        }
        catch (InvalidInitialPriceShareException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '111')
            ));
        }
        catch (InvalidSubscriptionPromotionNameException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '109')
            ));
        }
        catch (SubscriptionPromotionAlreadyExistsException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '113')
            ));
        }
        catch(Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '101')
            ));
        }

        Actions::redirect(DynamicalWeb::getRoute('finance_subscriptions/manage_subscription_plan',
            array('id' => $_GET['id'], 'callback' => '114')
        ));
    }