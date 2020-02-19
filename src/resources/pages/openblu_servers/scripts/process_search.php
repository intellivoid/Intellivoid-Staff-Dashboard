<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
use IntellivoidAccounts\Exceptions\HostNotKnownException;
use IntellivoidAccounts\Exceptions\InvalidSearchMethodException;
    use IntellivoidAccounts\Exceptions\TelegramClientNotFoundException;
use IntellivoidAccounts\Exceptions\UserAgentNotFoundException;
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
                'known_hosts', array('callback' => '100')
            ));
        }

        if(isset($_POST['value']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'known_hosts', array('callback' => '100')
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

            $KnownHost = $IntellivoidAccounts->getKnownHostsManager()->getHost(
                $_POST['by'], $_POST['value']
            );

            Actions::redirect(DynamicalWeb::getRoute(
                'view_known_host', array('id' => $KnownHost->ID)
            ));
        }
        catch(InvalidSearchMethodException $invalidSearchMethodException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'known_hosts', array('callback' => '103')
            ));
        }
        catch(HostNotKnownException $hostNotKnownException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'known_hosts', array('callback' => '101')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'known_hosts', array('callback' => '102')
            ));
        }
    }