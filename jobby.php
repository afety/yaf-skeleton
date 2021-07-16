<?php

// Add this line to your crontab file:
// * * * * * cd APPLICATION_PATH/application && php jobby.php 1>> /dev/null 2>&1

require_once "Common.php";

$commandPrefix = 'php ' . APPLICATION_CLI_PATH . ' ';

// 生产环境定时任务脚本
$jobs = [
    "updateDeptAndUser" => [ // 日志监控脚本
        'command' => "/cli/Crontab/updateDeptUserFromPs",  // 定时运行脚本命令
        'schedule' => '0 8,13 * * *',      // 时间
        'output' => '/dev/null',        // 脚本输出位置
        'enabled' => false,              // 是否启用该脚本
    ]
];

$jobby = new \Jobby\Jobby();
foreach ($jobs as $name => $cron) {
    $jobby->add($name, [
        'command' => $commandPrefix . " request_uri='{$cron['command']}'", // 脚本运行命令
        'schedule' => $cron['schedule'],            // 时间
        'output' => $cron['output'] ?? '/dev/null',                // 命令输出位置
        'enabled' => $cron['enabled'] ?? false,              // 是否启用该脚本
    ]);
}

$jobby->run();
