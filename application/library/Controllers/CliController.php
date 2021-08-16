<?php

namespace Library\Controllers;
/**
 * 默认CLI模块控制器抽象类
 * @package Base
 * @author zhangyang
 */
abstract class CliController extends AbstractController
{
    public function init()
    {
        parent::init();
        if ($this->getRequest()->getMethod() != 'CLI') {
            Tools::redirect('/index');
        } else {
            \Yaf\Dispatcher::getInstance()->disableView();
        }
    }
}
