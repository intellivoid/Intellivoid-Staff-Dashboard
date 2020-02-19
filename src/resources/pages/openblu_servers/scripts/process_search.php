<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
use OpenBlu\Exceptions\InvalidSearchMethodException;
use OpenBlu\Exceptions\VPNNotFoundException;
use OpenBlu\OpenBlu;

if(isset($_GET['action']))
    {
        if($_GET['action'] == 'search')
        {
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                process_search();
            }
        }
    }

    function process_search()
    {
        if(isset($_POST['by']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'openblu_servers', array('callback' => '100')
            ));
        }

        if(isset($_POST['value']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'openblu_servers', array('callback' => '100')
            ));
        }

        $OpenBlu = new OpenBlu();

        try
        {

            $KnownHost = $OpenBlu->getVPNManager()->getVPN(
                $_POST['by'], $_POST['value']
            );

            Actions::redirect(DynamicalWeb::getRoute(
                'view_known_host', array('id' => $KnownHost->ID)
            ));
        }
        catch(InvalidSearchMethodException $invalidSearchMethodException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'openblu_servers', array('callback' => '103')
            ));
        }
        catch(VPNNotFoundException $hostNotKnownException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'openblu_servers', array('callback' => '101')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'openblu_servers', array('callback' => '102')
            ));
        }
    }