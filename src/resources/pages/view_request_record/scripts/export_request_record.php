<?php

    use DynamicalWeb\DynamicalWeb;
    use IntellivoidAPI\Objects\RequestRecord;

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'export')
        {
            export_request_record();
        }
    }

    function export_request_record()
    {
        /** @var RequestRecord $RequestRecord */
        $RequestRecord = DynamicalWeb::getMemoryObject('request_record');
        $FileName = $RequestRecord->ReferenceID . '_export.json';
        $File = json_encode($RequestRecord->toArray());

        header('Content-Length: ' . strlen($File));
        header('Content-Type: application/json');
        header("Content-disposition: attachment; filename=\"$FileName\"");
        print($File);
        exit();
    }