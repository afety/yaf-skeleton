<?php

namespace Library\Controllers;

use Exception;
use Library\Utils\NewLog;

/**
 * admin模块控制器抽象类
 *
 */
abstract class WebController extends AbstractController
{
    protected $_code = '';
    protected $_open = true;

    /**
     * Web用户登录权限
     */
    protected $id = '';
    protected $employeeId = '';

    protected $uid = '';
    protected $user = null;
    protected $agentUid = '';
    protected $agentUser = '';

    protected $name = '';
    protected $email = '';
    protected $org = '';

    /**
     * @throws UserNotExistException
     * @throws Exception
     * @author tanghan <tanghan@ifeng.com>
     * @time 2020/12/31 20:56
     */
    public function init()
    {
        parent::init();

        $token = trim($this->getRequest()->getCookie('EamWebCookie') ?? '');

        if (empty($token)) {
            $this->redirect('/web/auth/login?url=' . joinUrl(APP_DOMAIN, $_SERVER['HTTP_WEBROUTER'] ?? ''));
        }

        $session = Session::getSession('Web', $token);

        $userInfo = $session->getAllValue();

        if (empty($userInfo)) {
            // 删除历史Cookie
//            $this->redirect('/web/auth/login?url=' . joinUrl(APP_DOMAIN, $_SERVER['HTTP_WEBROUTER'] ?? ''));
            $this->failure('Cookie失效,重新登陆', 401);
        }

        $this->id = $userInfo['id'];

        $this->uid = $userInfo['usr'];
        $this->agentUid = $this->uid;

        $this->user = BllUser::getInstance()->getUserByUid($this->uid);
        $this->agentUser = $this->user;

        // 并不需要这三样信息

        if (!isEmpty($agentUid = $userInfo['agentUid'] ?? '')) {
            // 若存在代理Uid
            $agentUser = BllUser::getInstance()->getUserByUid($agentUid);

            if (!isEmpty($agentUser)) {
                $this->user = $agentUser;
                $this->uid = $this->user->getUid();
            }
        }

        if (isEmpty($this->user)) {
            throw new UserNotExistException($this->uid);
        }
        assert($this->user instanceof User);

        $controllerName = $this->getRequest()->getControllerName();
        $this->_code = head(explode('_', $controllerName)); // 获取目录 只支持一级目录导航

        $this->employeeId = $this->user->getEmployeeId();

        // 校验权限
        if (!$this->checkPermission()) {
            $this->failure('无此操作权限', 403);
        }

        // 用户信息记录
        NewLog::userInfoLog(json_encode_c($userInfo));
    }

    /**Cookie配置错误：配置项错误
     * 权限检测
     * @return bool
     * @author tanghan <tanghan@ifeng.com>
     * @time 2020/7/29 10:20
     */
    public function checkPermission()
    {
        if (!$this->_open) return true;
        // 代理者权限校验
        $roles = $this->user->getRoles();
        foreach ($roles as $role) {
            assert($role instanceof Role);
            // 超级管理员 或则 有该权限
            if ($role->isSuperAdmin() || $role->hasPermissionDir($this->_code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取蛇形class名
     * @return mixed
     * @author tanghan <tanghan@ifeng.com>
     * @time 2020/7/29 10:20
     */
    protected function snakeClassName()
    {
        return str_replace('controller', '', strtolower(get_called_class()));
    }
}
