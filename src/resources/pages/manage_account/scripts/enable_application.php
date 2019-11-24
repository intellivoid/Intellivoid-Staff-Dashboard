<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\ApplicationAccessStatus;
use IntellivoidAccounts\Abstracts\ApplicationStatus;
use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationAccessSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\ApplicationSearchMethod;
use IntellivoidAccounts\Exceptions\ApplicationAccessNotFoundException;
use IntellivoidAccounts\Exceptions\ApplicationNotFoundException;
use IntellivoidAccounts\IntellivoidAccounts;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'enable_application')
        {
            if(isset($_GET['application_id']))
            {
                enable_application($_GET['application_id']);
            }
        }
    }

    function enable_application(string $application_id)
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

        try
        {
            $Application = $IntellivoidAccounts->getApplicationManager()->getApplication(
                ApplicationSearchMethod::byApplicationId, $application_id
            );
        }
        catch (ApplicationNotFoundException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array('callback' => '123', 'id' => $_GET['id'])));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array('callback' => '122', 'id' => $_GET['id'])));
        }

        $Timestamp = (int)time();
        $Application->Status = ApplicationStatus::Active;
        $Application->LastUpdatedTimestamp = $Timestamp;

        try
        {
            $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);
            Actions::redirect(DynamicalWeb::getRoute('manage_account',
                array('id' => $_GET['id'], 'callback' => '125')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account',
                array('id' => $_GET['id'], 'callback' => '122')
            ));
        }
    }
