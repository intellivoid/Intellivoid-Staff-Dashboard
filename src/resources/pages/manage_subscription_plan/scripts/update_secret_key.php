<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;
    use IntellivoidAccounts\Utilities\Hashing;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_secret_key')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    update_secret_key();
                }
                catch(Exception $e)
                {
                    Actions::redirect(DynamicalWeb::getRoute('manage_application',
                        array('id' => $_GET['id'], 'callback' => '113')
                    ));
                }
            }
        }
    }

    function update_secret_key()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        $Timestamp = (int)time();
        $Application->SecretKey = Hashing::applicationSecretKey($Application->PublicAppId, $Timestamp);
        $Application->LastUpdatedTimestamp = $Timestamp;

        try
        {
            $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);
            Actions::redirect(DynamicalWeb::getRoute('manage_application',
                array('id' => $_GET['id'], 'callback' => '101')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }
    }