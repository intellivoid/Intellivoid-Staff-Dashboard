<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
use IntellivoidAccounts\Abstracts\AccountStatus;
use IntellivoidAccounts\Abstracts\AuditEventType;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'set_status')
        {
            try
            {
                set_status();
            }
            catch(Exception $exception)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                    'callback' => '112', 'id' => $_GET['id']
                )));
            }
        }
    }

    function set_status()
    {
        if(isset($_GET['status']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
            )));
        }

        $Status = 0;

        switch(strtoupper($_GET['status']))
        {
            case "ACTIVE":
                $Status = AccountStatus::Active;
                break;

            case "SUSPENDED";
                $Status = AccountStatus::Suspended;
                break;

            case "LIMITED":
                $Status = AccountStatus::Limited;
                break;

            case "VERIFICATION_REQUIRED":
                $Status = AccountStatus::VerificationRequired;
                break;

            case "GBA_MODE":
                $Status = AccountStatus::BlockedDueToGovernmentBackedAttack;
                break;

            default:
                Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                    'callback' => '114', 'id' => $_GET['id']
                )));
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

        $Account = $IntellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, (int)$_GET['id']);
        $Account->Status = (int)$Status;
        $IntellivoidAccounts->getAccountManager()->updateAccount($Account);

        Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
            'callback' => '113', 'id' => $_GET['id']
        )));
    }
