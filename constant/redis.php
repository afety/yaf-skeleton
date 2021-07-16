<?php

$config = new Yaf\Config\Ini(APPLICATION_CONFIGURATION_PATH, getYafEnviron());
define("REDIS_HOST", $config->get('redis.params.host'));
define("REDIS_PORT", $config->get('redis.params.port') ?? 6379);
define("REDIS_USERNAME", $config->get('redis.params.username'));
define("REDIS_PASSWORD", $config->get('redis.params.password'));
define("REDIS_DATABASE", intval($config->get('redis.params.database')));
define("REDIS_PREFIX", $config->get('redis.params.prefix'));
