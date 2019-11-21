<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\AuditEventType;
    use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
    use IntellivoidAccounts\IntellivoidAccounts;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'clear_information')
        {
            try
            {
                clear_information();
            }
            catch(Exception $exception)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                    'callback' => '106', 'id' => $_GET['id']
                )));
            }
        }

        if($_GET['action'] == 'update_information')
        {
            try
            {
                update_name();
                update_birthday();

                Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                    'callback' => '103', 'id' => $_GET['id']
                )));
            }
            catch(Exception $exception)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                    'callback' => '104', 'id' => $_GET['id']
                )));
            }
        }
    }

    function update_name()
    {
        if(isset($_POST['first_name']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
            )));
        }

        if(isset($_POST['last_name']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
            )));
        }

        if(preg_match("/^([a-zA-Z' ]+)$/",$_POST['first_name']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '101', 'id' => $_GET['id']
            )));
        }

        if(strlen($_POST['first_name']) > 46)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '101', 'id' => $_GET['id']
            )));
        }

        if(strlen($_POST['first_name']) < 1)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '101', 'id' => $_GET['id']
            )));
        }

        if(preg_match("/^([a-zA-Z' ]+)$/",$_POST['last_name']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '102', 'id' => $_GET['id']
            )));
        }

        if(strlen($_POST['last_name']) > 64)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '102', 'id' => $_GET['id']
            )));
        }

        if(strlen($_POST['last_name']) < 1)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '102', 'id' => $_GET['id']
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
        $Account->PersonalInformation->FirstName = $_POST['first_name'];
        $Account->PersonalInformation->LastName = $_POST['last_name'];
        $IntellivoidAccounts->getAccountManager()->updateAccount($Account);
    }

    function update_birthday()
    {
        if(isset($_POST['dob_year']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
            )));
        }

        if(isset($_POST['dob_month']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
            )));
        }

        if(isset($_POST['dob_day']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '100', 'id' => $_GET['id']
            )));
        }

        $DOB_Year = (int)$_POST['dob_year'];
        $DOB_Month = (int)$_POST['dob_month'];
        $DOB_Day = (int)$_POST['dob_day'];

        if($DOB_Year < 1970)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '105', 'id' => $_GET['id']
            )));
        }

        if($DOB_Year > ((int)date('Y') - 13))
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '105', 'id' => $_GET['id']
            )));
        }

        if($DOB_Month < 1)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '105', 'id' => $_GET['id']
            )));
        }

        if($DOB_Month > 12)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '105', 'id' => $_GET['id']
            )));
        }

        if($DOB_Day < 1)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '105', 'id' => $_GET['id']
            )));
        }

        if($DOB_Day > 31)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
                'callback' => '105', 'id' => $_GET['id']
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
        $Account->PersonalInformation->BirthDate->Year = $DOB_Year;
        $Account->PersonalInformation->BirthDate->Month = $DOB_Month;
        $Account->PersonalInformation->BirthDate->Day = $DOB_Day;
        $IntellivoidAccounts->getAccountManager()->updateAccount($Account);
        $IntellivoidAccounts->getAuditLogManager()->logEvent($Account->ID, AuditEventType::PersonalInformationUpdated);
    }

    function clear_information()
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
        $Account->PersonalInformation->FirstName = null;
        $Account->PersonalInformation->LastName = null;
        $Account->PersonalInformation->BirthDate->Year = 0;
        $Account->PersonalInformation->BirthDate->Month = 0;
        $Account->PersonalInformation->BirthDate->Day = 0;
        $IntellivoidAccounts->getAccountManager()->updateAccount($Account);
        $IntellivoidAccounts->getAuditLogManager()->logEvent($Account->ID, AuditEventType::PersonalInformationUpdated);

        Actions::redirect(DynamicalWeb::getRoute('manage_account', array(
            'callback' => '107', 'id' => $_GET['id']
        )));
    }