<?php

use App\Kernel;

require dirname(__DIR__).'/vendor/autoload.php';

session_start();

(new Kernel())->handle();
