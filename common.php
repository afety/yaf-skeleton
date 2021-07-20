<?php

use Yaf\Application;

mb_internal_encoding('UTF-8');
define("APPLICATION_PATH", realpath(dirname(__FILE__)));
require APPLICATION_PATH . '/vendor/autoload.php';

// 配置文件
$environ = empty(getYafEnviron()) ? 'develop' : getYafEnviron();
$filepath = APPLICATION_PATH . '/conf/' . $environ . '.ini';
define('APPLICATION_CONFIGURATION_PATH', $filepath);

// cli脚本位置
define("APPLICATION_CLI_PATH", joinPaths(APPLICATION_PATH, 'cli/cli.php'));

// 提前启动应用加载配置文件，方便常量目录获取配置
$app = (new Application(APPLICATION_CONFIGURATION_PATH))->bootstrap();
$app->getDispatcher()->returnResponse(false);

// 被migration引入后会造成问题，因此应用的分发到各自的入口文件执行
// 区分是命令行访问还是其他形式
