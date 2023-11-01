<?php

use App\Service\Router;

require_once dirname(__DIR__).'/src/BaseFunctions.php';
require_once dirname(__DIR__).'/src/Autoloader.php';

define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

session_start();

Router::redirect();
