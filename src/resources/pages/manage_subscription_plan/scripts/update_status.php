<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\SubscriptionPlanStatus;
use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
use IntellivoidAccounts\Objects\SubscriptionPlan;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_status')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    update_status();
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

    function update_status()
    {
        /** @var SubscriptionPlan $SubscriptionPlan */
        $SubscriptionPlan = DynamicalWeb::getMemoryObject('subscription_plan');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        if(isset($_POST['status']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_subscription_plan',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        switch($_POST['status'])
        {
            case 'available':
                $SubscriptionPlan->Status = SubscriptionPlanStatus::Available;
                break;

            case 'unavailable':
                $SubscriptionPlan->Status = SubscriptionPlanStatus::Unavailable;
                break;

            default:
                Actions::redirect(DynamicalWeb::getRoute('manage_subscription_plan',
                    array('id' => $_GET['id'], 'callback' => '104')
                ));
        }

        $IntellivoidAccounts->getSubscriptionPlanManager()->updateSubscriptionPlan($SubscriptionPlan);

        Actions::redirect(DynamicalWeb::getRoute('manage_subscription_plan',
            array('id' => $_GET['id'])
        ));
    }