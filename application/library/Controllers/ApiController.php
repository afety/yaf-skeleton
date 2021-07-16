<?php

namespace Library\Controllers;

use Yaf\Registry;

/**
 * Api模块控制器抽象类
 * ip控制拆解为总控IP过滤与业务IP过滤
 */
abstract class ApiController extends AbstractController
{
    protected $ips = [];

    public function init()
    {
        parent::init();

        $config = Registry::get('config');
        $whiteIps = array_map('trim', array_values(array_filter(explode(',', $config->get('ips')))));

        // 将ips提取到配置文件，采用控制器名称做key
        $whiteIps = array_merge($whiteIps, $this->ips);

        try {
            $ip = getRemoteAddr();
            // 关闭校验
//            if (!in_array($ip, $whiteIps)) {
//                $this->failure('无权访问该接口', 403, null);
//            }

        } catch (\Exception $e) {
            $this->failure($e->getMessage(), $e->getCode());
        }
    }

}
