<?php /** @noinspection PhpDuplicateSwitchCaseBodyInspection */

    use DynamicalWeb\HTML;
    HTML::importScript('render_alert');

    if(isset($_GET['callback']))
    {
        if(isset($_GET['error']))
        {
            if($_GET['error'] == 'khm')
            {
                switch((int)$_GET['callback'])
                {
                    case 0:
                        RenderAlert('Internal Server Error', "danger", "mdi-alert-circle");
                        break;

                    case 100:
                    case 101:
                        RenderAlert('Missing parameters for internal request', "danger", "mdi-alert-circle");
                        break;

                    case 102:
                        RenderAlert('Invalid User Agent', "danger", "mdi-alert-circle");
                        break;

                    case 103:
                        RenderAlert('Invalid IP Address', "danger", "mdi-alert-circle");
                        break;

                    case 104:
                        RenderAlert('Internal Server Error at Intellivoid Accounts', "danger", "mdi-alert-circle");
                        break;

                    case 105:
                        RenderAlert('IP Address blocked for security reasons', "danger", "mdi-alert-circle");
                        break;
                }
            }

            if($_GET['error'] == 'otl')
            {
                switch((int)$_GET['callback'])
                {
                    case 0:
                        RenderAlert('Internal Server Error', "danger", "mdi-alert-circle");
                        break;

                    case 113:
                    case 100:
                    case 101:
                    case 102:
                        RenderAlert('Missing parameters for internal request', "danger", "mdi-alert-circle");
                        break;

                    case 103:
                        RenderAlert('Invalid Authentication Code', "danger", "mdi-alert-circle");
                        break;

                    case 104:
                        RenderAlert('Internal Server Error', "danger", "mdi-alert-circle");
                        break;

                    case 105:
                        RenderAlert('The authentication code was already used', "danger", "mdi-alert-circle");
                        break;

                    case 106:
                        RenderAlert('The authentication code is unavailable at this time', "danger", "mdi-alert-circle");
                        break;

                    case 107:
                        RenderAlert('The authentication code has expired', "danger", "mdi-alert-circle");
                        break;

                    case 108:
                        RenderAlert('The account was not found', "danger", "mdi-alert-circle");
                        break;

                    case 109:
                        RenderAlert('The account has been suspended', "danger", "mdi-alert-circle");
                        break;

                    case 110:
                        RenderAlert('The account needs to be verified before it can be used', "danger", "mdi-alert-circle");
                        break;

                    case 111:
                        RenderAlert('The vendor name is invalid', "danger", "mdi-alert-circle");
                        break;

                    case 112:
                        RenderAlert('Invalid Host ID', "danger", "mdi-alert-circle");
                        break;

                    case 114:
                        RenderAlert('Invalid User Agent', "danger", "mdi-alert-circle");
                        break;
                }
            }
        }
        else
        {
            switch((int)$_GET['callback'])
            {
                case 100:
                    RenderAlert('There was an issue with your request', "danger", "mdi-alert-circle");
                    break;

                case 101:
                    RenderAlert('You do not have the required permissions to access this resource', "danger", "mdi-alert-circle");
                    break;
            }
        }
    }