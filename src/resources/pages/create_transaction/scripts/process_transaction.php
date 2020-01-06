<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;
use IntellivoidAccounts\Abstracts\SearchMethods\AccountSearchMethod;
use IntellivoidAccounts\Exceptions\AccountNotFoundException;
    use IntellivoidAccounts\Exceptions\InvalidFundsValueException;
    use IntellivoidAccounts\Exceptions\InvalidVendorException;
    use IntellivoidAccounts\IntellivoidAccounts;
    use IntellivoidAccounts\Utilities\Validate;

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
        if(isset($_POST['account_id']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '100')));
        }

        if(isset($_POST['vendor']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '100')));
        }

        if(isset($_POST['amount']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '100')));
        }

        $IntellivoidAccounts = new IntellivoidAccounts();

        $Vendor = $_POST['vendor'];
        $Amount = (float)$_POST['amount'];

        if(Validate::vendor($Vendor) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '102')));
        }

        if($Amount == 0)
        {
            Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '103')));
        }

        if($Amount > 0)
        {
            try
            {
                $IntellivoidAccounts->getTransactionManager()->addFunds(
                    $_POST['account_id'], $_POST['vendor'], (float)$_POST['amount']
                );
            }
            catch (AccountNotFoundException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '104')));
            }
            catch (InvalidFundsValueException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '103')));
            }
            catch (InvalidVendorException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '102')));
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '101')));
            }
        }
        if($Amount < 0)
        {
            try
            {
                $Account = $IntellivoidAccounts->getAccountManager()->getAccount(AccountSearchMethod::byId, $_POST['account_id']);

                if(($_POST['amount'] * -1) > $Account->Configuration->Balance)
                {
                    Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '106')));
                }
            }
            catch (AccountNotFoundException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '104')));
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '101')));
            }

            try
            {
                $IntellivoidAccounts->getTransactionManager()->processPayment(
                    $_POST['account_id'], $_POST['vendor'], (float)$_POST['amount'] * -1
                );
            }
            catch (AccountNotFoundException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '104')));
            }
            catch (InvalidFundsValueException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '103')));
            }
            catch (InvalidVendorException $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '102')));
            }
            catch(Exception $e)
            {
                Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '101')));
            }
        }

        Actions::redirect(DynamicalWeb::getRoute('create_transaction', array('callback' => '105')));
    }