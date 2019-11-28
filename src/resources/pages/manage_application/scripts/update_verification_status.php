<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Abstracts\ApplicationFlags;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'update_verification_status')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                try
                {
                    update_verification_status();
                }
                catch(Exception $e)
                {
                    Actions::redirect(DynamicalWeb::getRoute('manage_application',
                        array('id' => $_GET['id'], 'callback' => '113')
                    ));
                }
            }
        }
    }

    function update_verification_status()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");

        if(isset($_POST['verification_status']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('manage_application',
                array('id' => $_GET['id'], 'callback' => '100')
            ));
        }

        switch($_POST['verification_status'])
        {
            case 'none':
                $Application->remove_flag(ApplicationFlags::Verified);
                $Application->remove_flag(ApplicationFlags::Official);
                $Application->remove_flag(ApplicationFlags::Untrusted);
                break;

            case 'verified':
                $Application->apply_flag(ApplicationFlags::Verified);
                $Application->remove_flag(ApplicationFlags::Official);
                $Application->remove_flag(ApplicationFlags::Untrusted);
                break;

            case 'official':
                $Application->remove_flag(ApplicationFlags::Verified);
                $Application->apply_flag(ApplicationFlags::Official);
                $Application->remove_flag(ApplicationFlags::Untrusted);
                break;

            case 'untrusted':
                $Application->remove_flag(ApplicationFlags::Verified);
                $Application->remove_flag(ApplicationFlags::Official);
                $Application->apply_flag(ApplicationFlags::Untrusted);
                break;

            default:
                Actions::redirect(DynamicalWeb::getRoute('manage_application',
                    array('id' => $_GET['id'], 'callback' => '104')
                ));
        }

        $IntellivoidAccounts->getApplicationManager()->updateApplication($Application);
        Actions::redirect(DynamicalWeb::getRoute('manage_application',
            array('id' => $_GET['id'], 'callback' => '105')
        ));
    }