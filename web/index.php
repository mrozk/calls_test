<?php
$basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR;

require_once $basePath . "app/App.php";
$config = require_once $basePath . "config.php";

$app = new Application($config);
$app->run();
