<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
use IntellivoidAccounts\Exceptions\ApplicationAccessNotFoundException;
use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
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
                'application_access', array('callback' => '100')
            ));
        }

        if(isset($_POST['value']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'application_access', array('callback' => '100')
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
            $ApplicationAccess = $IntellivoidAccounts->getCrossOverAuthenticationManager()->getApplicationAccessManager()->getApplicationAccess(
                $_GET['by'], $_GET['value']
            );

            Actions::redirect(DynamicalWeb::getRoute(
                'view_application_access', array('id' => $ApplicationAccess->ID)
            ));
        }
        catch(InvalidSearchMethodException $invalidSearchMethodException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'application_access', array('callback' => '103')
            ));
        }
        catch(ApplicationAccessNotFoundException $accountNotFoundException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'application_access', array('callback' => '101')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'application_access', array('callback' => '102')
            ));
        }
    }