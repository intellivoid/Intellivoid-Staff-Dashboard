<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Page;
    use DynamicalWeb\Runtime;
    use sws\sws;

    Runtime::import('SecuredWebSessions');

    $unauthorized_pages = [
        'auth/login'
    ];

    execute_authentication_check($unauthorized_pages);

    function execute_authentication_check(array $unauthorized_pages)
    {
        /** @var sws $sws */
        $sws = DynamicalWeb::setMemoryObject('sws', new sws());

        if($sws->WebManager()->isCookieValid('staff_secured_web_session') == false)
        {
            $Cookie = $sws->CookieManager()->newCookie('staff_secured_web_session', 86400, false);

            $Cookie->Data = array(
                'session_active' => false,
                'account_pubid' => null,
                'account_email' => null,
                'account_username' => null,
                'roles' => array(),
                'cache' => array(),
                'cache_refresh' => 0
            );

            $sws->CookieManager()->updateCookie($Cookie);
            $sws->WebManager()->setCookie($Cookie);

            if($Cookie->Name == null)
            {
                print('There was an issue with the security check, Please refresh the page');
                exit();
            }

            if(isset($_GET['callback']))
            {
                header('Refresh: 2; URL=/auth/login?callback=' . urlencode($_GET['callback']));
            }
            else
            {
                header('Refresh: 2; URL=/auth/login');
            }

            Page::staticResponse('Intellivoid Staff', 'Loading Web Resources', 'Loading resources, this may take a while.');
            exit();
        }

        try
        {
            $Cookie = $sws->WebManager()->getCookie('staff_secured_web_session');
        }
        catch(Exception $exception)
        {
            Page::staticResponse(
                'Intellivoid Error',
                'Web Sessions Issue',
                'There was an issue with your Web Session, try clearing your cookies and try again'
            );
            exit();
        }

        DynamicalWeb::setMemoryObject('(cookie)web_session', $Cookie);

        define('WEB_SESSION_ACTIVE', $Cookie->Data['session_active'], false);
        define('WEB_ACCOUNT_PUBID', $Cookie->Data['account_pubid'], false);
        define('WEB_ACCOUNT_EMAIL', $Cookie->Data['account_email'], false);
        define('WEB_ACCOUNT_USERNAME', $Cookie->Data['account_username'], false);

        if(WEB_SESSION_ACTIVE == false)
        {
            $redirect = true;

            foreach($unauthorized_pages as $page)
            {
                if(APP_CURRENT_PAGE == $page)
                {
                    $redirect = false;
                }
            }

            if($redirect == true)
            {
                Actions::redirect(DynamicalWeb::getRoute('auth/login'));
            }
        }
        else
        {
            $redirect = false;

            foreach($unauthorized_pages as $page)
            {
                if(APP_CURRENT_PAGE == $page)
                {
                    $redirect = true;
                }
            }

            if($redirect == true)
            {
                Actions::redirect(DynamicalWeb::getRoute('index'));
            }
        }
    }

