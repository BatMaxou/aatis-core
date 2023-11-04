<?php

use Aatis\Core\Service\Router;

require dirname(__DIR__) . '/src/BaseFunctions.php';
require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

session_start();

Router::redirect();
