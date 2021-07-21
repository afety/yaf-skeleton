<?php

use Library\Utils\NewLog;
use Yaf\Plugin_Abstract;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;

class UserPlugin extends Plugin_Abstract
{
    public function routerStartup(Request_Abstract $request, Response_Abstract $response)
    {
    }

    public function routerShutdown(Request_Abstract $request, Response_Abstract $response)
    {
    }

    public function preDispatch(Request_Abstract $request, Response_Abstract $response)
    {
        $_SERVER['CUSTOM_TAG'] = $request->getQuery('custom_tag') ?? md5Chars();

        define('TAG', $_SERVER['CUSTOM_TAG']);

        NewLog::infoLog($_SERVER);
    }

    public function dispatchLoopStartup(Request_Abstract $request, Response_Abstract $response)
    {
    }

    public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response)
    {
    }

    public function postDispatch(Request_Abstract $request, Response_Abstract $response)
    {
//        var_dump($response->getBody());die();
    }
}