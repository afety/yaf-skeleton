<?php

use Library\Controllers\AbstractController;
use Library\Exceptions\JobFailedException;

/**
 * 当有未捕获的异常, 则控制流会流到这里
 */
class ErrorController extends AbstractController
{

    /**
     * @param Exception $exception
     * @author tanghan <tanghan@ifeng.com>
     * @time 2021/4/12 15:29
     */
    public function errorAction(Exception $exception)
    {

        /**
         * 区分测试环境与生产环境
         * 测试环境显示错误详细信息
         * 生产环境屏蔽详细信息
         */

        if ($exception->getCode() >= 500) {
            // 发送邮件通知或特殊处理
            if (isProduct()) {

            }
        }

        $this->failure($exception->getMessage(), $exception->getCode());
    }

}
