<?php

    use DynamicalWeb\Actions;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use sws\sws;

    HTML::importScript('auth_api');

    if(isset($_POST['authentication_code']) == false)
    {
        Actions::redirect(DynamicalWeb::getRoute('login', array(
            'callback' => '100'
        )));
    }

    try
    {
        $KnownHost = khm_GetHostId(CLIENT_REMOTE_HOST, CLIENT_USER_AGENT);
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('login', array(
            'callback' => $exception->getCode(),
            'error' => 'khm'
        )));
    }

    try
    {
        $Response = otl_VerifyCode($_POST['authentication_code'], $KnownHost, CLIENT_USER_AGENT);
    }
    catch(Exception $exception)
    {
        Actions::redirect(DynamicalWeb::getRoute('login', array(
            'callback' => $exception->getCode(),
            'error' => 'otl'
        )));
    }

    /** @var sws $sws */
    $sws = DynamicalWeb::setMemoryObject('sws', new sws());
    $Cookie = $sws->WebManager()->getCookie('staff_secured_web_session');

    $Cookie->Data['session_active'] = true;
    $Cookie->Data['account_pubid'] = $Response['id'];
    $Cookie->Data['account_email'] = $Response['email'];
    $Cookie->Data['account_username'] = $Response['username'];
    $Cookie->Data['roles'] = $Response['roles'];

    $sws->CookieManager()->updateCookie($Cookie);

    Actions::redirect(DynamicalWeb::getRoute('index'));