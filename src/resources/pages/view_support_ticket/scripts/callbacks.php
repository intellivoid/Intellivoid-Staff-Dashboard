<?php

    use DynamicalWeb\HTML;

    if(isset($_GET['callback']))
    {
        HTML::importScript('render_alert');

        switch((int)$_GET['callback'])
        {
            case 100:
                RenderAlert('Internal Server Error', "danger", "mdi-alert-circle");
                break;

            case 101:
                RenderAlert('The given status is invalid', "danger", "mdi-alert-circle");
                break;

            case 102:
                RenderAlert('The given administrator notes are invalid', "danger", "mdi-alert-circle");
                break;

        }
    }