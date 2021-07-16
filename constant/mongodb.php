<?php

$config = new Yaf\Config\Ini(APPLICATION_CONFIGURATION_PATH, getYafEnviron());
define("MONGODB_HOST", $config->get('mongodb.params.host'));
define("MONGODB_PORT", $config->get('mongodb.params.port'));
define("MONGODB_USERNAME", $config->get('mongodb.params.username'));
define("MONGODB_PASSWORD", $config->get('mongodb.params.password'));
define("MONGODB_DATABASE", $config->get('mongodb.params.database'));
