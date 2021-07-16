<?php

$config = new Yaf\Config\Ini(APPLICATION_CONFIGURATION_PATH, getYafEnviron());
define("MYSQL_HOST", $config->get('mysql.params.host'));
define("MYSQL_PORT", $config->get('mysql.params.port') ?? 3306);
define("MYSQL_USERNAME", $config->get('mysql.params.username'));
define("MYSQL_PASSWORD", $config->get('mysql.params.password'));
define("MYSQL_DATABASE", $config->get('mysql.params.database'));