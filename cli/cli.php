<?php

require 'common.php';

use Yaf\Application;
use Yaf\Request\Simple;

$app = (new Application(APPLICATION_CONFIGURATION_PATH))->bootstrap();
$app->getDispatcher()->returnResponse(false)->getDispatcher()->dispatch(new Simple());
