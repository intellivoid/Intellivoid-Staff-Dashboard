<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\ApplicationStatus;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'enable_application')
        {
            try
            {
                enable_application();
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_application',
                    array('id' => $_GET['id'], 'callback' => '113')
                ));
            }
        }
    }

    function enable_application()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        $Timestamp = (int)time();
        $Application->Status = ApplicationStatus::Active;
        $Application->LastUpdatedTimestamp = $Timestamp;

        try
        {
            $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);

            Actions::redirect(DynamicalWeb::getRoute('manage_application',
                array('id' => $_GET['id'], 'callback' => '119')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application',
                array('pub_id' => $_GET['pub_id'], 'callback' => '113')
            ));
        }
    }