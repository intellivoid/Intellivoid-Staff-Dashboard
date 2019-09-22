<?php


    use DynamicalWeb\DynamicalWeb;

    $AuthenticationConfiguration = DynamicalWeb::getConfiguration('auth');
    if($AuthenticationConfiguration['localhost_development'])
    {
        define('AVATAR_ENDPOINT', "http://" . $AuthenticationConfiguration['local_host'] . "/user/contents/public/avatar");
    }
    else
    {
        define('AVATAR_ENDPOINT', "https://" . $AuthenticationConfiguration['remote_endpoint'] . "/user/contents/public/avatar");
    }

    function getAvatarUrl(string $resource_id, string $resource_name): string
    {
        $resource_id = urlencode($resource_id);
        $resource_name = urlencode($resource_name);
        return AVATAR_ENDPOINT . '?user_id=' . $resource_id . '&resource=' . $resource_name;
    }