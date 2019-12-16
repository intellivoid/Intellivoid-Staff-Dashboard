<?php

    if(isset($_GET['action']))
    {
        if($_GET['action'] == 'stream')
        {
            if(isset($_GET['blob']))
            {
                if($_GET['blob'] == 'feature_builder')
                {
                    download_feature_builder();
                }
            }
        }
    }

    function download_feature_builder()
    {
        $python_file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'feature_builder.py');

        header('Content-Length: ' . strlen($python_file));
        header('Content-Type: application/x-python-code');
        header("Content-disposition: attachment; filename=\"feature_builder.py\"");
        print($python_file);
        exit();
    }