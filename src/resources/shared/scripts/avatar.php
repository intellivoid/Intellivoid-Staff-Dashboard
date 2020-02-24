<?php


    use COASniffle\COASniffle;
    use DynamicalWeb\Runtime;

    Runtime::import('COASniffle');
    new COASniffle();

    function getAvatarUrl(string $resource_id, string $resource_name): string
    {
        return \COASniffle\Handlers\COA::getAvatarUrl($resource_name, $resource_id);
    }

    function getApplicationUrl(string $resource_id, string $resource_name): string
    {
        return \COASniffle\Handlers\COA::getBrandUrl($resource_name, $resource_id);
    }

