<?php

use Library\Controllers\AbstractController;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        \Library\Utils\Redis\Redis::select('test')->set('test-123', '123');
        \Library\Utils\Redis\Redis::set('test-456', '123');
        die();
    }
}
