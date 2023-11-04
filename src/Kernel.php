<?php

namespace Aatis\Core;

use Aatis\Core\Service\Router;
use Aatis\Core\Service\ContainerBuilder;

class Kernel
{
    public function handle()
    {
        $ctx = [
            'env' => 'dev',
            'debug' => true,
        ];

        (new ContainerBuilder($ctx, ROOT . 'src'))->build();
        // Container builder job
        // get router by container
        // $router->redirect();
    }
}
