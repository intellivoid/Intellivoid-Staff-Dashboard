<?php

    use DynamicalWeb\DynamicalWeb;
use IntellivoidAPI\Objects\ExceptionRecord;
use IntellivoidAPI\Objects\RequestRecord;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'export')
        {
            export_exception_record();
        }
    }

    function export_exception_record()
    {
        /** @var ExceptionRecord $ExceptionRecord */
        $ExceptionRecord = DynamicalWeb::getMemoryObject('exception_record');
        $FileName = 'exception-' . $ExceptionRecord->ID . '_export.json';
        $File = json_encode($ExceptionRecord->toArray());

        header('Content-Length: ' . strlen($File));
        header('Content-Type: application/json');
        header("Content-disposition: attachment; filename=\"$FileName\"");
        print($File);
        exit();
    }