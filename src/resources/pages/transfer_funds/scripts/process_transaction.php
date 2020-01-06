<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;
    use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\InsufficientFundsException;
    use IntellivoidAccounts\Exceptions\InvalidFundsValueException;
    use IntellivoidAccounts\Exceptions\InvalidVendorException;
    use IntellivoidAccounts\IntellivoidAccounts;

    Runtime::import('IntellivoidAccounts');

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'process_transaction')
        {
            process_transaction();
        }
    }

    function process_transaction()
    {
        if(isset($_POST['sending_account_id']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '100')));
        }

        if(isset($_POST['receiving_account_id']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '100')));
        }

        if(isset($_POST['amount']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '100')));
        }

        $IntellivoidAccounts = new IntellivoidAccounts();
        $Amount = (float)$_POST['amount'];

        if($Amount == 0)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '103')));
        }

        if($Amount < 0)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '103')));
        }

        try
        {
            $IntellivoidAccounts->getTransactionManager()->transferFunds(
                $_POST['sending_account_id'], $_POST['receiving_account_id'], $_POST['amount']
            );
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '105')));
        }
        catch (AccountNotFoundException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '104')));
        }
        catch (InsufficientFundsException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '106')));
        }
        catch (InvalidFundsValueException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '103')));
        }
        catch (InvalidVendorException $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '102')));
        }
        catch(Exception $e)
        {
            Actions::redirect(DynamicalWeb::getRoute('transfer_funds', array('callback' => '101')));
        }


    }