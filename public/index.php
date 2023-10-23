<?php

require_once '../src/database/Connection.php';
require_once '../src/base_functions/BaseFunctions.php';

define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));

session_start();

require_once '../src/service/Router.php';

Router::redirect();
