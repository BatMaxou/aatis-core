<?php

namespace Aatis\Core;

use Aatis\Core\Service\Router;
use Aatis\Core\Service\ContainerBuilder;

class Kernel
{
    public function handle(): void
    {
        $ctx = [
            'env' => 'dev',
            'debug' => true,
        ];

        $container = (new ContainerBuilder($ctx, $_ENV['DOCUMENT_ROOT'].'/../src'))->build();

        /**
         * @var Router $router
         */
        $router = $container->get('Aatis\Core\Service\Router');

        $router->redirect();
    }
}
