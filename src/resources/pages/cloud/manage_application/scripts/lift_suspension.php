<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\ApplicationFlags;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'lift_suspension')
        {
            try
            {
                lift_suspension();
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                    array('id' => $_GET['id'], 'callback' => '113')
                ));
            }
        }
    }

    function lift_suspension()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        if($Application->Status == ApplicationStatus::Suspended)
        {
            $Application->Status = ApplicationStatus::Active;
            $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '109')
            ));
        }


        Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
            array('id' => $_GET['id'], 'callback' => '108')
        ));
    }