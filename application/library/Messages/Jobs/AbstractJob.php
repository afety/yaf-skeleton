<?php

namespace Library\Messages\Jobs;

use Dao\FailureJob;
use Exception;
use Library\Exceptions\JobFailedException;
use Library\Messages\Workers\AbstractWorker;
use Library\Utils\NewLog;
use Resque;
use Resque_Event;
use Resque_Job;

abstract class AbstractJob extends Resque_Job
{
    /**
     * 工厂方法中使用args进行的赋值, 父类的getArguments方法不适用此处
     * @time 2021/3/25 14:27
     * @var array
     */
    public $args = [];

    /**
     * 参数默认值用于修复Resque_Factory_Job 生成JOB实例时候的错误
     * MyJob constructor.
     * @param string $queue
     * @param array $payload
     */
    public function __construct($queue = '', $payload = [])
    {
        parent::__construct($queue, $payload);

    }

    /**
     * @param array $args
     * @param string $queueName
     * @return bool|string
     * @time 2021/3/25 16:14
     */
    public static function addQueue(array $args, string $queueName = '')
    {
        $calledClass = get_called_class();
        if (empty($queueName)) {
            $className = array_values(array_filter(explode('\\', $calledClass)));
            $queueName = end($className);
        }
        return Resque::enqueue($queueName, $calledClass, $args);
    }

    /**
     * 采用Job名称作为Queue名称
     * @param string $workerClass
     * @return AbstractWorker
     * @time 2021/3/25 17:14
     */
    public static function createWorker(string $workerClass = '')
    {
        $calledClass = get_called_class();
        $className = array_values(array_filter(explode('\\', $calledClass)));
        $className = end($className);

        if (empty($workerClass)) {
            $workerName = str_replace('Job', 'Worker', $className);
            $workerClass = 'Library\Messages\Workers\\' . $workerName;
        }

        return new $workerClass([$className]);
    }

    /**
     * 消费前操作
     * @time 2021/3/29 10:15
     */
    public function setUp()
    {

    }

    /**
     * 队列消费
     * @return bool|void
     * @throws JobFailedException
     * @time 2021/3/25 14:10
     */
    final public function perform()
    {
        try {
            // 注册JOB执行失败的监听函数
            Resque_Event::listen('onFailure', function ($exception, $job) {
                return $this->failureHandler($exception, $job);
            });

            $this->run($this->args);
        } catch (Exception $exception) {
            throw new JobFailedException($exception->getMessage());
        }
    }

    /**
     * JOB运行失败后的处理
     * 记录进入mysql库 方便重开机制
     * 将重开机制迁移到Mysql后 在管理界面重做一套？
     * TODO: 重试机制是否提供web页面，还是直接使用命令行操作
     * @param $exception
     * @param Resque_Job $job
     * @return bool
     * @time 2021/4/1 17:16
     */
    private function failureHandler($exception, Resque_Job $job)
    {
        try {
            FailureJob::addFiledJob($job, $exception);
        } catch (Exception $e) {
            //TODO: 错误处理失效  需要发送邮件
            NewLog::jobFailLog($e->getMessage());
        }

        return true;
    }

    /**
     * JOB运行的方法
     * @param array $args
     * @return mixed
     * @time 2021/3/25 16:14
     */
    abstract public function run(array $args);

    /**
     * 消费完毕的后置操作
     * @time 2021/3/29 10:16
     */
    public function tearDown()
    {

    }

    /**
     * 任务失败后主动抛异常
     * @param string $msg
     * @throws JobFailedException
     * @time 2021/3/25 13:51
     */
    protected function failed(string $msg)
    {
        throw new JobFailedException($msg);
    }
}