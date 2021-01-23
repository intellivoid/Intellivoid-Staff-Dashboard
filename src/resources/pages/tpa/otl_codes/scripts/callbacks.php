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
                RenderAlert('No results were found', "danger", "mdi-alert-circle");
                break;

            case 102:
                RenderAlert('There was an error while trying to process the search', "danger", "mdi-alert-circle");
                break;

            case 103:
                RenderAlert('The given search method is not valid', "danger", "mdi-alert-circle");
                break;

            case 104:
                RenderAlert('The Authentication Request was not found', "danger", "mdi-alert-circle");
                break;

            case 105:
                RenderAlert('Internal Server Error', "danger", "mdi-alert-circle");
                break;

        }
    }