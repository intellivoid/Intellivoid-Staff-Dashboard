<?php


    use DynamicalWeb\DynamicalWeb;

    $AuthenticationConfiguration = DynamicalWeb::getConfiguration('auth');
    if($AuthenticationConfiguration['localhost_development'])
    {
        define('AVATAR_ENDPOINT', "http://" . $AuthenticationConfiguration['local_host'] . "/user/contents/public/avatar");
        define('ICON_ENDPOINT', "http://" . $AuthenticationConfiguration['local_host'] . "/user/contents/public/application");
    }
    else
    {
        define('AVATAR_ENDPOINT', "https://" . $AuthenticationConfiguration['remote_endpoint'] . "/user/contents/public/avatar");
        define('ICON_ENDPOINT', "https://" . $AuthenticationConfiguration['remote_endpoint'] . "/user/contents/public/application");
    }

    function getAvatarUrl(string $resource_id, string $resource_name): string
    {
        $resource_id = urlencode($resource_id);
        $resource_name = urlencode($resource_name);
        return AVATAR_ENDPOINT . '?user_id=' . $resource_id . '&resource=' . $resource_name;
    }

    function getApplicationUrl(string $resource_id, string $resource_name): string
    {
        $resource_id = urlencode($resource_id);
        $resource_name = urlencode($resource_name);
        return ICON_ENDPOINT . '?app_id=' . $resource_id . '&resource=' . $resource_name;
    }

