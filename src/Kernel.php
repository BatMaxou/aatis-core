<?php

namespace Aatis\Core;

use Aatis\Core\Service\Router;

class Kernel
{
    public function handle()
    {
        $router = new Router();
        // Container builder job
        $router->redirect();
    }
}
