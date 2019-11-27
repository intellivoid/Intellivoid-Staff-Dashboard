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
                RenderAlert('The secret key signature has been updated', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 102:
                RenderAlert('The given authentication request for this application is invalid', "danger", "mdi-alert-circle");
                break;

            case 103:
                RenderAlert('The authentication method for this application has been updated', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 104:
                RenderAlert('The given verification status is invalid', "danger", "mdi-alert-circle");
                break;

            case 105:
                RenderAlert('The verification status has been changed', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 106:
                RenderAlert('The application cannot be suspended because it\'s already suspended', "danger", "mdi-alert-circle");
                break;

            case 107:
                RenderAlert('The application has been suspended', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 108:
                RenderAlert('The suspension cannot be lifted because the application is not suspended', "danger", "mdi-alert-circle");
                break;

            case 109:
                RenderAlert('The suspension has been lifted', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 110:
                RenderAlert('There was an error while trying to process the file upload', "danger", "mdi-alert-circle");
                break;

            case 111:
                RenderAlert('There was a system error while trying to save the file to the disk', "danger", "mdi-alert-circle");
                break;

            case 112:
                RenderAlert('This file type is unsupported, it should be JPEG or PNG', "danger", "mdi-alert-circle");
                break;

            case 113:
                RenderAlert('There was an internal server error', "danger", "mdi-alert-circle");
                break;

            case 114:
                RenderAlert('The image is too small', "danger", "mdi-alert-circle");
                break;

            case 115:
                RenderAlert('The file cannot be processed correctly by the system', "danger", "mdi-alert-circle");
                break;

            case 116:
                RenderAlert('The logo has been updated successfully', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 117:
                RenderAlert('The permissions has been updated', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 118:
                RenderAlert('The application has been disabled', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 119:
                RenderAlert('The application has been enabled', "success", "mdi-checkbox-marked-circle-outline");
                break;

        }
    }