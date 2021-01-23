<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\KnownHost;

    /**
     * Blocks a host
     *
     * @param KnownHost $knownHost
     * @param IntellivoidAccounts $intellivoidAccounts
     */
    function block_host(KnownHost $knownHost, IntellivoidAccounts $intellivoidAccounts)
    {
        $knownHost->Blocked = true;

        try
        {
            $intellivoidAccounts->getKnownHostsManager()->updateKnownHost($knownHost);
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'cloud/known_hosts', array('callback' => '105')
            ));
        }

        if(isset($_GET['redirect']))
        {
            if($_GET['redirect'] == 'cloud/known_hosts')
            {
                Actions::redirect(DynamicalWeb::getRoute(
                    'cloud/known_hosts', array('callback' => '106')
                ));
            }
        }

        Actions::redirect(DynamicalWeb::getRoute(
            'cloud/view_known_host', array('id' => $knownHost->ID)
        ));
    }

    /**
     * Unblocks a host
     *
     * @param KnownHost $knownHost
     * @param IntellivoidAccounts $intellivoidAccounts
     */
    function unblock_host(KnownHost $knownHost, IntellivoidAccounts $intellivoidAccounts)
    {
        $knownHost->Blocked = false;
        try
        {
            $intellivoidAccounts->getKnownHostsManager()->updateKnownHost($knownHost);
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'cloud/known_hosts', array('callback' => '105')
            ));
        }

        if(isset($_GET['redirect']))
        {
            if($_GET['redirect'] == 'cloud/known_hosts')
            {
                Actions::redirect(DynamicalWeb::getRoute(
                    'cloud/known_hosts', array('callback' => '106')
                ));
            }
        }

        Actions::redirect(DynamicalWeb::getRoute(
            'cloud/view_known_host', array('id' => $knownHost->ID)
        ));
    }