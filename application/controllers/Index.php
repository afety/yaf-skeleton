<?php

use Library\Controllers\AbstractController;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        echo APP_NAME;
    }
}
