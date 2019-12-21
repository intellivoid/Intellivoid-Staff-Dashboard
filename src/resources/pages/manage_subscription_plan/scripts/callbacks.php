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
                RenderAlert('There was an internal server error', "danger", "mdi-alert-circle");
                break;

            case 102:
                RenderAlert('The given status is invalid for this Subscription Plan', "danger", "mdi-alert-circle");
                break;

        }
    }