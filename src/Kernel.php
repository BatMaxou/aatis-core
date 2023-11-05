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

        $container = (new ContainerBuilder($ctx, ROOT . '../src'))->build();

        /**
         * @var Router $router
         */
        $router = $container->get('Aatis\Core\Service\Router');

        $router->redirect();
    }
}
