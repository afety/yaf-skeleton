<?php

namespace Library\Controllers;


abstract class WechatController extends AbstractController
{
    protected $token = '';
    protected $uid = '';
    protected $employeeId = '';
    protected $name = '';
    protected $user = null;

    /**
     * @time 2021/6/10 15:18
     */
    public function init()
    {
        parent::init();

        $wechatToken = $this->getRequest()->getServer('HTTP_WECHATTOKEN');
        if (empty($wechatToken) && empty($webToken)) {
            $this->failure('权限错误:Token值为空', 401);
        }
        $this->token = $wechatToken;

        $redisConnect = RedisCache::connect();
        $redisConnect->select(0);

        $userInfo = $redisConnect->hGetAll('WechatAuth:' . $this->token);
        if (isEmpty($userInfo)) {
            $this->failure('权限错误：用户信息为空', 401);
        }

        if (isEmpty($uid = $userInfo['uid'] ?? '') || isEmpty($name = $userInfo['name'] ?? '')) {
            $this->failure('权限错误：用户信息缺失');
        }

        if (isEmpty($user = User::getInstance()->getUserByUid($uid))) {
            $this->failure('员工信息查询失败', 500);
        }

        $this->uid = $uid;
        $this->name = $name;
        $this->user = $user;
    }
}