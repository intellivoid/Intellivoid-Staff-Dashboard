<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAccounts\Exceptions\DatabaseException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Objects\COA\Application;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'delete_application')
        {
            try
            {
                delete_application();
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('manage_application',
                    array('id' => $_GET['id'], 'callback' => '113')
                ));
            }
        }
    }

    /**
     * @throws DatabaseException
     */
    function delete_application()
    {
        /** @var Application $Application */
        $Application = DynamicalWeb::getMemoryObject('application');

        /** @var IntellivoidAccounts $IntellivoidAccounts */
        $IntellivoidAccounts = DynamicalWeb::getMemoryObject("intellivoid_accounts");


        $IntellivoidAccounts->getApplicationManager()->deleteApplication($Application);

        Actions::redirect(DynamicalWeb::getRoute('applications',
            array('callback' => '106')
        ));
    }