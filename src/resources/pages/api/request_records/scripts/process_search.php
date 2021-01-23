<?php


    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAPI\Exceptions\InvalidSearchMethodException;
    use IntellivoidAPI\Exceptions\RequestRecordNotFoundException;
    use IntellivoidAPI\IntellivoidAPI;

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
                'api/request_records', array('callback' => '100')
            ));
        }

        if(isset($_POST['value']) == false)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'api/request_records', array('callback' => '100')
            ));
        }

        $IntellivoidAPI = new IntellivoidAPI();

        try
        {
            $RequestRecord = $IntellivoidAPI->getRequestRecordManager()->getRequestRecord(
                $_POST['by'], $_POST['value']
            );

            Actions::redirect(DynamicalWeb::getRoute('api/view_request_record', array(
                'id' => $RequestRecord->ID
            )));
        }
        catch(InvalidSearchMethodException $invalidSearchMethodException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'api/request_records', array('callback' => '103')
            ));
        }
        catch(RequestRecordNotFoundException $authenticationRequestNotFoundException)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'api/request_records', array('callback' => '101')
            ));
        }
        catch(Exception $exception)
        {
            Actions::redirect(DynamicalWeb::getRoute(
                'api/request_records', array('callback' => '102')
            ));
        }
    }