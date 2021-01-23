<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\KnownHostsSearchMethod;
    use IntellivoidAccounts\Abstracts\SearchMethods\LoginRecordMultiSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'export_data')
        {
            try
            {
                export_data();
            }
            catch(Exception $exception)
            {
                Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
                    'callback' => '126', 'id' => $_GET['id']
                )));
            }
        }
    }

    function export_data()
    {
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

        $Account = $IntellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, (int)$_GET['id']);

        $Response = array();
        $Response['export_details'] = array(
            'timestamp' => (int)time(),
            'iva_version' => '2.0',
            'verified' => true
        );
        $Response['user'] = $Account->toArray();

        $Response['cloud/known_hosts'] = array();
        try
        {
            foreach($Account->Configuration->KnownHosts->KnownHosts as $host_id)
            {
                try
                {
                    $KnownHost = $IntellivoidAccounts->getKnownHostsManager()->getHost(KnownHostsSearchMethod::byId, (int)$host_id);
                    $Response['cloud/known_hosts'][] = $KnownHost->toArray();
                }
                catch (Exception $exception)
                {
                    continue;
                }
            }
        }
        catch(Exception $exception)
        {
            $Response['cloud/known_hosts'] = null;
        }

        $Response['login_history'] = array();
        try
        {
            $Results = $IntellivoidAccounts->getLoginRecordManager()->searchRecords(
                LoginRecordMultiSearchMethod::byAccountId, $Account->ID,
                0, (int)0
            );
            foreach($Results as $loginRecord)
            {
                $Response['login_history'][] = $loginRecord;
            }
        }
        catch(Exception $exception)
        {
            $Response['login_history'] = null;
        }

        $Response['cloud/devices'] = array();
        try
        {
            $DeviceResults = array();
            foreach($Account->Configuration->KnownHosts->KnownHosts as $host_id)
            {
                $Results = $IntellivoidAccounts->getTrackingUserAgentManager()->getRecordsByHost($host_id);
                foreach($Results as $device)
                {
                    $Response['cloud/devices'][$device['id']] = $device;
                }
            }
        }
        catch(Exception $exception)
        {
            $Response['cloud/devices'] = null;
        }


        $Output = json_encode($Response);

        header('Content-disposition: attachment; filename=iva-' . strtolower($Account->Username) . '.json');
        header('Content-Type: application/json');
        header('Content-Size: ' . strlen($Output));
        print($Output);
        exit();
    }