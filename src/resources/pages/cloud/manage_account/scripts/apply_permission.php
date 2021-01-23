<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\AuditEventType;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'set_permission')
        {
            try
            {
                apply_permission();
            }
            catch(Exception $exception)
            {
                Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
                    'callback' => '111', 'id' => $_GET['id']
                )));
            }
        }
    }

    function apply_permission()
    {
        if(isset($_GET['permission']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
            )));
        }

        $Role = "None";

        switch(strtoupper($_GET['permission']))
        {
            case "ADMINISTRATOR":
            case "MODERATOR":
            case "SUPPORT":
                $Role = strtoupper($_GET['permission']);
                break;

            default:
                Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
                    'callback' => '108', 'id' => $_GET['id']
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
        $Account->Configuration->Roles->apply_role($Role);
        $IntellivoidAccounts->getAccountManager()->updateAccount($Account);

        Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
            'callback' => '109', 'id' => $_GET['id']
        )));
    }
