<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Exceptions\InvalidBillingCycleException;
    use IntellivoidAccounts\Exceptions\InvalidCyclePriceException;
    use IntellivoidAccounts\Exceptions\InvalidFeatureException;
    use IntellivoidAccounts\Exceptions\InvalidInitialPriceException;
    use IntellivoidAccounts\Exceptions\InvalidSubscriptionPlanNameException;
    use IntellivoidAccounts\Exceptions\SubscriptionPlanAlreadyExistsException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use IntellivoidAccounts\Objects\Subscription\Feature;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'create_subscription')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                create_subscription_plan();
            }
        }
    }

    function create_subscription_plan()
    {
        if(isset($_POST['plan_name']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '100',
                'id' => $_GET['id'],
                'param' => 'plan_name'
            )));
        }

        if(isset($_POST['initial_price']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '100',
                'id' => $_GET['id'],
                'param' => 'initial_price'
            )));
        }

        if(isset($_POST['cycle_price']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '100',
                'id' => $_GET['id'],
                'param' => 'cycle_price'
            )));
        }

        if(isset($_POST['unix_billing_cycle']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '100',
                'id' => $_GET['id'],
                'param' => 'unix_billing_cycle'
            )));
        }

        if(isset($_POST['features']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '100',
                'id' => $_GET['id'],
                'param' => 'features'
            )));
        }

        if(json_decode($_POST['features']) == null)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '120',
                'id' => $_GET['id']
            )));
        }

        if((float)$_POST['initial_price'] < 0)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '121',
                'id' => $_GET['id']
            )));
        }

        if((float)$_POST['initial_price'] < 0)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '122',
                'id' => $_GET['id']
            )));
        }

        if((float)$_POST['unix_billing_cycle'] < 86400)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '123',
                'id' => $_GET['id']
            )));
        }

        if((float)$_POST['unix_billing_cycle'] < 86400)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '123',
                'id' => $_GET['id']
            )));
        }


        // Validate features
        $features = json_decode($_POST['features'], true);
        $features_array = array();
        foreach($features as $feature)
        {
            $FeatureObject = Feature::fromArray($feature);

            if($FeatureObject->Name == null)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                    'callback' => '124',
                    'id' => $_GET['id']
                )));
            }

            if($FeatureObject->Value == null)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                    'callback' => '124',
                    'id' => $_GET['id']
                )));
            }

            $features_array[] = $FeatureObject;
        }

        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        try
        {
            $IntellivoidAccounts->getSubscriptionPlanManager()->createSubscriptionPlan(
                (int)$Application->ID, $_POST['plan_name'], $features_array,
                (float)$_POST['initial_price'], (float)$_POST['cycle_price'], (int)$_POST['unix_billing_cycle']
            );
        }
        catch (InvalidBillingCycleException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '125',
                'id' => $_GET['id']
            )));
        }
        catch (InvalidCyclePriceException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '126',
                'id' => $_GET['id']
            )));
        }
        catch (InvalidFeatureException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '124',
                'id' => $_GET['id']
            )));
        }
        catch (InvalidInitialPriceException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '127',
                'id' => $_GET['id']
            )));
        }
        catch (InvalidSubscriptionPlanNameException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '128',
                'id' => $_GET['id']
            )));
        }
        catch (SubscriptionPlanAlreadyExistsException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '129',
                'id' => $_GET['id']
            )));
        }
        catch(Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
                'callback' => '113',
                'id' => $_GET['id']
            )));
        }

        Actions::redirect(DynamicalWeb::getRoute('manage_application', array(
            'callback' => '130',
            'id' => $_GET['id']
        )));
    }