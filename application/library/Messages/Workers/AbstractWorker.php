<?php

namespace Library\Messages\Workers;

use Library\Exceptions\NormalException;
use Library\Exceptions\WorkerRuntimeException;
use Psr\Log\LogLevel;
use Resque;
use Resque_Log;
use Resque_Worker;

abstract class AbstractWorker extends Resque_Worker
{
    /**
     * workers数量
     * @time 2021/3/24 15:25
     * @var int
     */
    private $count = 1;
    /**
     * 队列数量为0 时候 间隔多久扫描队列
     * @time 2021/3/24 15:26
     * @var int
     */
    private $interval = 5;
    /**
     * 是否打印详细日志
     * @time 2021/3/24 15:26
     * @var bool
     */
    private $verbose = false;
    /**
     * 队列无数据时候是否阻塞
     * @time 2021/3/24 15:27
     * @var bool
     */
    private $blocking = true;

    /**
     * NotificationWorker constructor.
     * @param array $queues 操作的队列列表
     * @param int $count worker数量
     * @param int $interval 操作队列的间隔时间，同时也是阻塞时长
     * @param bool $verbose 是否打印详细日志
     * @param bool $blocking 队列无数据时是否阻塞
     */
    public function __construct(array $queues, int $count = 1,
                                int $interval = 5, bool $verbose = false, bool $blocking = true)
    {
        parent::__construct($queues);

        $this->count = $count;
        $this->interval = $interval;
        $this->verbose = $verbose;
        $this->blocking = $blocking;
    }

    /**
     * @param int $count
     * @return $this
     * @throws NormalException
     * @time 2021/3/24 15:36
     */
    public function setCount(int $count = 1)
    {
        if ($count < 1) throw new NormalException("Workers num cannot less than 1");

        $this->count = $count;
        return $this;
    }

    /**
     * @param int $interval
     * @return $this
     * @throws NormalException
     * @time 2021/3/24 15:36
     */
    public function setInterval(int $interval = 1)
    {
        if ($interval < 1) throw new NormalException("interval cannot less than 1");

        $this->interval = $interval;
        return $this;
    }

    /**
     * @param bool $verbose
     * @return $this
     * @time 2021/3/24 15:37
     */
    public function setVerbose(bool $verbose = false)
    {
        $this->verbose = $verbose;
        return $this;
    }

    /**
     * @param bool $blocking
     * @return $this
     * @time 2021/3/24 15:37
     */
    public function setBlocking(bool $blocking = true)
    {
        $this->blocking = $blocking;
        return $this;
    }

    /**
     * 自定义worker运行函数，方便控制及日志增添
     * 切勿使用原有work函数
     * @throws WorkerRuntimeException
     * @time 2021/3/24 15:44
     */
    public function run()
    {
        $queues = $this->queues();
        if (isEmpty($this->queues())) {
            throw new WorkerRuntimeException("Set QUEUE env var containing the list of queues to work.\n");
        }


        $count = $this->count;
        $interval = $this->interval;
        $blocking = $this->blocking;
        $logger = new Resque_Log($this->verbose);

        if ($count > 1) {
            $children = array();
            $GLOBALS['send_signal'] = FALSE;

            $die_signals = array(SIGTERM, SIGINT, SIGQUIT);
            $all_signals = array_merge($die_signals, array(SIGUSR1, SIGUSR2, SIGCONT, SIGPIPE));

            for ($i = 0; $i < $count; ++$i) {
                //成功时，在父进程执行线程内返回产生的子进程的PID，
                //在子进程执行线程内返回0。失败时，在 父进程上下文返回-1，不会创建子进程，并且会引发一个PHP错误
                $pid = Resque::fork();
                if ($pid == -1) {
                    // 子进程fork失败
                    throw new WorkerRuntimeException("Could not fork worker " . $i . "\n");
                } elseif (!$pid) {
                    $worker = new Resque_Worker($queues);
                    $worker->setLogger($logger);
                    $worker->hasParent = TRUE;
                    fwrite(STDOUT, '*** Starting worker ' . $worker . "\n");
                    $worker->work($interval);
                    break;
                } else {
                    // fork 成功
                    $children[$pid] = 1;
                    while (count($children) == $count) {
                        if (!isset($registered)) {
                            // declare 控制ZEND引擎每执行一条低级语句就去检查是否有未处理的信号
                            declare(ticks=1);
                            foreach ($all_signals as $signal) {
                                // 信号处理注册
                                pcntl_signal($signal, function ($signal) {
                                    $GLOBALS['send_signal'] = $signal;
                                });
                            }

                            // 设置写入的PID文件地址，暂时不用
//                            $PIDFILE = getenv('PIDFILE');
//                            if ($PIDFILE) {
//                                if(file_put_contents($PIDFILE, getmypid()) === false){
//                                    $logger->log(Psr\Log\LogLevel::NOTICE, 'Could not write PID information to {pidfile}', array('pidfile' => $PIDFILE));
//                                    die(2);
//                                }
//                            }

                            $registered = TRUE;
                        }

                        // 返回退出的子进程进程号，发生错误时返回-1,
                        //如果提供了 WNOHANG作为option（wait3可用的系统）并且没有可用子进程时返回0。
                        $childPID = pcntl_waitpid(-1, $childStatus, WNOHANG);
                        if ($childPID != 0) {
                            // 子进程dead
                            fwrite(STDOUT, "*** A child worker died: {$childPID}\n");
                            unset($children[$childPID]);
                            $i--;
                        }
                        // 休眠 0.25秒
                        usleep(250000);

                        // 接收到信号
                        if ($GLOBALS['send_signal'] !== FALSE) {
                            foreach ($children as $k => $v) {
                                posix_kill($k, $GLOBALS['send_signal']);
                                if (in_array($GLOBALS['send_signal'], $die_signals)) {
                                    pcntl_waitpid($k, $childStatus);
                                }
                            }

                            if (in_array($GLOBALS['send_signal'], $die_signals)) {
                                exit;
                            }
                            $GLOBALS['send_signal'] = FALSE;
                        }
                    }
                }
            }
        } else {
            // 开启单个worker
            $worker = new Resque_Worker($queues);
            $worker->setLogger($logger);
            $worker->hasParent = FALSE;

//            $PIDFILE = getenv('PIDFILE');
//            if ($PIDFILE) {
//                if(file_put_contents($PIDFILE, getmypid()) === false) {
//                    $logger->log(Psr\Log\LogLevel::NOTICE, 'Could not write PID information to {pidfile}', array('pidfile' => $PIDFILE));
//                    die(2);
//                }
//            }

            $logger->log(LogLevel::NOTICE, 'Starting worker {worker}', array('worker' => $worker));
            $worker->work($interval, $blocking);
        }
    }
}