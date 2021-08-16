<?php

namespace Dao;

use Exception;
use Library\Exceptions\AbstractException;
use Library\Messages\Jobs\AbstractJob;
use Resque_Job;

class FailureJob extends MysqlModel
{
    protected $table = 'failure_job';

    protected $fillable = [
        'status', 'job_class', 'args', 'queue_name', 'exception_message', 'exception_trace'
    ];

    protected $fieldsCommon = [
        'status' => '是否已重新处理 0否 1是',
        'job_class' => 'Job类',
        'args' => '参数',
        'queue_name' => '队列名称',
        'exception_message' => '错误提示',
        'exception_trace' => '错误请求跟踪',
    ];

    // Getter Func
    
    /*
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /*
     * @return string
     */
    public function getJobClass()
    {
        return $this->job_class;
    }

    /*
     * @return string
     */
    public function getArgs()
    {
        return $this->args;
    }

    /*
     * @return string
     */
    public function getQueueName()
    {
        return $this->queue_name;
    }

    /*
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->exception_message;
    }

    /*
     * @return string
     */
    public function getExceptionTrace()
    {
        return $this->exception_trace;
    }

    // Setter Func
    
    /*
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
        return $this;
    }

    /*
     * @param string $jobClass
     * @return $this
     */
    public function setJobClass(string $jobClass)
    {
        $this->job_class = $jobClass;
        return $this;
    }

    /*
     * @param string $args
     * @return $this
     */
    public function setArgs(string $args)
    {
        $this->args = $args;
        return $this;
    }

    /*
     * @param string $queueName
     * @return $this
     */
    public function setQueueName(string $queueName)
    {
        $this->queue_name = $queueName;
        return $this;
    }

    /*
     * @param string $exceptionMessage
     * @return $this
     */
    public function setExceptionMessage(string $exceptionMessage)
    {
        $this->exception_message = $exceptionMessage;
        return $this;
    }

    /*
     * @param string $exceptionTrace
     * @return $this
     */
    public function setExceptionTrace(string $exceptionTrace)
    {
        $this->exception_trace = $exceptionTrace;
        return $this;
    }

    public static function addFiledJob(Resque_Job $job, Exception $exception)
    {
        // 添加失败任务记录
    }
}