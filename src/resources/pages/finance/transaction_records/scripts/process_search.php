<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
use IntellivoidAccounts\Exceptions\SubscriptionPlanNotFoundException;
use IntellivoidAccounts\Exceptions\SubscriptionPromotionNotFoundException;
    use IntellivoidAccounts\IntellivoidAccounts;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'search')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                process_search();
            }
        }
    }

    function process_search()
    {
        if(isset($_POST['by']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'finance_subscriptions/manage_subscription_promotion', array('callback' => '100')
            ));
        }

        if(isset($_POST['value']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'finance_subscriptions/manage_subscription_promotion', array('callback' => '100')
            ));
        }

        if(isset(DynamicalWeb::$globalObjects["intellivoid_accounts"]) == false)
        {
            /** @var IntellivoidAccounts $IntellivoidAccounts */
            $IntellivoidAccounts = DynamicalWeb::setMemoryObject(
                "intellivoid_accounts", new IntellivoidAccounts()
            );
        }
        else
        {
            /** @var IntellivoidAccounts $IntellivoidAccounts */
            $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");
        }

        try
        {
            $ActiveSubscription = $IntellivoidAccounts->getSubscriptionManager()->getSubscription(
                $_POST['by'], $_POST['value']
            );
            // TODO: Add redirect here
        }
        catch(InvalidSearchMethodException $invalidSearchMethodException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'finance_subscriptions/manage_subscription_promotion', array('callback' => '103')
            ));
        }
        catch(SubscriptionPlanNotFoundException $authenticationRequestNotFoundException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'finance_subscriptions/manage_subscription_promotion', array('callback' => '101')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'finance_subscriptions/manage_subscription_promotion', array('callback' => '102')
            ));
        }
    }