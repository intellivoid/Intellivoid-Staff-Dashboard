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
                RenderAlert('The given First Name is invalid', "danger", "mdi-alert-circle");
                break;

            case 102:
                RenderAlert('The given Last Name is invalid', "danger", "mdi-alert-circle");
                break;

            case 103:
                RenderAlert('Personal Information Updated', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 104:
                RenderAlert('There was an issue while trying to update the information', "danger", "mdi-alert-circle");
                break;

            case 105:
                RenderAlert('The given date is invalid', "danger", "mdi-alert-circle");
                break;

            case 106:
                RenderAlert('There was an issue while trying to clear the information', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 107:
                RenderAlert('The information was cleared', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 108:
                RenderAlert('The given permission is not valid', "warning", "mdi-alert-circle");
                break;

            case 109:
                RenderAlert('Permission has been applied', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 110:
                RenderAlert('Permission has been revoked', "success", "mdi-checkbox-marked-circle-outline");
                break;
        }
    }