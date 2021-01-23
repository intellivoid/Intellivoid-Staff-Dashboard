<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\AuditEventType;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
use IntellivoidAccounts\Abstracts\SearchMethods\TelegramClientSearchMethod;
use IntellivoidAccounts\IntellivoidAccounts;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'send_notification')
        {
            try
            {
                send_notification();
            }
            catch(Exception $exception)
            {
                Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
                    'callback' => '115', 'id' => $_GET['id']
                )));
            }
        }
    }

    function send_notification()
    {
        if(isset($_POST['tg-message']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
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
        if($Account->Configuration->VerificationMethods->TelegramClientLinked == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
                'callback' => '116', 'id' => $_GET['id']
            )));
        }

        $Client = $IntellivoidAccounts->getTelegramClientManager()->getClient(
            TelegramClientSearchMethod::byId, $Account->Configuration->VerificationMethods->TelegramLink->ClientId
        );
        $IntellivoidAccounts->getTelegramService()->sendNotification($Client, "Intellivoid Staff", $_POST['tg-message']);
        Actions::redirect(DynamicalWeb::getRoute('cloud/manage_account', array(
            'callback' => '117', 'id' => $_GET['id']
        )));
    }
