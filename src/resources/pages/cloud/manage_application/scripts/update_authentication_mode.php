<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\AuthenticationMode;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_authentication_mode')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    update_authentication_mode();
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

    function update_authentication_mode()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        if(isset($_POST['authentication_type']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        switch($_POST['authentication_type'])
        {
            case 'redirect':
                $Application->AuthenticationMode = AuthenticationMode::Redirect;
                break;

            case 'placeholder':
                $Application->AuthenticationMode = AuthenticationMode::ApplicationPlaceholder;
                break;

            case 'code':
                $Application->AuthenticationMode = AuthenticationMode::Code;
                break;

            default:
                Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
                    array('id' => $_GET['id'], 'callback' => '103')
                ));
        }

        $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);
        Actions::redirect(DynamicalWeb::getRoute('cloud/manage_application',
            array('id' => $_GET['id'], 'callback' => '103')
        ));
    }