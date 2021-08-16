<?php

namespace Library\Controllers;

use Library\Utils\NewLog;
use Yaf\Dispatcher;

/**
 * 控制器抽象类
 */
abstract class AbstractController extends \Yaf\Controller_Abstract
{

    /**
     * 分页页号
     * @time 2020/10/27 14:46
     * @var int
     */
    protected $pageNum = 1;

    /**
     * 每页内容数量
     * @time 2020/10/27 14:47
     * @var int
     */
    protected $pageSize = 10;

    /**
     * @time 2021/3/25 15:49
     */
    public function init()
    {
        Dispatcher::getInstance()->disableView();

        $this->pageNum = intval($this->getRequestData('pageNum') ?? $this->pageNum);
        $this->pageSize = intval($this->getRequestData('pageSize') ?? $this->pageSize);
    }

    /**
     * @return array|mixed|null
     */
    public function getRequestData()
    {
        $args = func_get_args();

        return getRequestData($this->getRequest(), ...$args);
    }

    /**
     * @param bool $data
     * @param string $msg
     * @param int $code
     * @time 2021/3/25 15:49
     */
    public function success($data = true, $msg = '', $code = 200)
    {
        $resData = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        $this->sendResponse($resData);
    }

    /**
     * @param array $resData
     * @param bool $stop
     * @time 2021/3/25 15:49
     */
    private function sendResponse(array $resData, bool $stop = false)
    {
        $response = $this->getResponse();
        $data = json_encode_c($resData);
        $response->setBody($data);
//        $response->response();
        NewLog::responseLog($data);
        // 响应发送完毕后就停止运行
        if ($stop) {
            $response->response();
            die();
        }
    }

    /**
     * @param string $msg
     * @param int $code
     * @param bool $data
     * @time 2021/3/25 15:49
     */
    public function failure($msg = '', $code = 400, $data = false)
    {
        $resData = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        $this->sendResponse($resData, true);
    }
}
