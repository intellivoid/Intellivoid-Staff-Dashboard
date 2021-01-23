<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'suspend_application')
        {
            try
            {
                suspend_application();
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                    array('id' => $_GET['id'], 'callback' => '113')
                ));
            }
        }
    }

    function suspend_application()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        if($Application->Status == ApplicationStatus::Suspended)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '106')
            ));
        }

       $Application->Status = ApplicationStatus::Suspended;

        $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);
        Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
            array('id' => $_GET['id'], 'callback' => '107')
        ));
    }