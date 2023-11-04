<?php

use Aatis\Core\Kernel;

require dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

session_start();

(new Kernel())->handle();
