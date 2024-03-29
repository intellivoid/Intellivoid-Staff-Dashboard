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

            case 103:
                RenderAlert('The initial price cannot have a value that\'s lower than 0', "danger", "mdi-alert-circle");
                break;

            case 104:
                RenderAlert('The cycle price cannot have a value that\'s lower than 0', "danger", "mdi-alert-circle");
                break;

            case 105:
                RenderAlert('The billing cycle cannot have a value that\'s lower than 1', "danger", "mdi-alert-circle");
                break;

            case 106:
                RenderAlert('The given data for features contains malformed JSON data that cannot be parsed', "danger", "mdi-alert-circle");
                break;

            case 107:
                RenderAlert('One or more features are invalid', "danger", "mdi-alert-circle");
                break;

            case 108:
                RenderAlert('The properties has been updated successfully', "success", "mdi-checkbox-marked-circle-outline");
                break;

            case 109:
                RenderAlert('The the given promotional code is invalid', "danger", "mdi-alert-circle");
                break;

            case 110:
                RenderAlert('The account ID was not found in the system', "danger", "mdi-alert-circle");
                break;

            case 111:
                RenderAlert('The initial price share cannot have a value that\'s lower than 0', "danger", "mdi-alert-circle");
                break;

            case 112:
                RenderAlert('The cycle price share cannot have a value that\'s lower than 0', "danger", "mdi-alert-circle");
                break;

            case 113:
                RenderAlert('The promotion already exists', "danger", "mdi-alert-circle");
                break;

            case 114:
                RenderAlert('The promotion has been created successfully', "success", "mdi-checkbox-marked-circle-outline");
                break;

        }
    }