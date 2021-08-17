<?php

use Yaf\Config\Ini;

$config = new Ini(APPLICATION_CONFIGURATION_PATH, getYafEnviron());
define('APP_NAME', $config->get('app_name'));

define("DAO_DIR", joinPaths(APPLICATION_PATH, '/application/models/Dao'));
define('DAO_NAMESPACE', 'Dao');
define('DAO_MYSQL_MODEL_BASE_CLASS', "MysqlModel");

define('LOG_DIR', $config->get('log.dir') ?? joinPaths(APPLICATION_PATH, 'log'));