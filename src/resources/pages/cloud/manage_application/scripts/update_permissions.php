<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\AccountRequestPermissions;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_permissions')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    update_permissions();
                }
                catch(Exception $e)
                {
                    Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                        array('id' => $_GET['id'], 'callback' => '113')
                    ));
                }
            }
        }
    }

    function is_checked(string $parameter): bool
    {
        if(isset($_POST[$parameter]))
        {
            if($_POST[$parameter] == 'on')
            {
                return true;
            }
        }

        return false;
    }

    function update_permissions()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        $Application->Permissions = [];

        if(is_checked('perm_view_personal_information'))
        {
            $Application->apply_permission(AccountRequestPermissions::ReadPersonalInformation);
        }

        if(is_checked('perm_make_purchases'))
        {
            $Application->apply_permission(AccountRequestPermissions::MakePurchases);
        }

        if(is_checked('perm_telegram_notifications'))
        {
            $Application->apply_permission(AccountRequestPermissions::TelegramNotifications);
        }

        if(is_checked('perm_view_email_address'))
        {
            $Application->apply_permission(AccountRequestPermissions::ViewEmailAddress);
        }

        $Timestamp = (int)time();
        $Application->LastUpdatedTimestamp = $Timestamp;

        try
        {
            $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '117')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '113')
            ));
        }
    }