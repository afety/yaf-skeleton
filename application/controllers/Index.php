<?php

use Illuminate\Support\Facades\Redis;
use Library\Controllers\AbstractController;
use Library\Utils\RedisCache;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        echo APP_NAME;
        echo 'Start';
        die();
    }
}
