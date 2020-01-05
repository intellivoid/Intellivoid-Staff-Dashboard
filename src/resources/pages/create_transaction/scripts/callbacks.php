<?php

    use DynamicalWeb\HTML;

    if(isset($_GET['callback']))
    {
        HTML::importScript('render_alert');

        switch((int)$_GET['callback'])
        {
            case 100:
                RenderAlert('There was a problem with the request, please make sure you are using an up to date browser', "danger", "mdi-alert-circle");
                break;

            case 101:
                RenderAlert('Internal Server Error', "danger", "mdi-alert-circle");
                break;

            case 102:
                RenderAlert('The given vendor is invalid', "danger", "mdi-alert-circle");
                break;

            case 103:
                RenderAlert('The amount is invalid', "danger", "mdi-alert-circle");
                break;

            case 104:
                RenderAlert('The account was not found', "danger", "mdi-alert-circle");
                break;

            case 105:
                RenderAlert('the transaction has been processed successfully', "success", "mdi-checkbox-marked-circle");
                break;


        }
    }