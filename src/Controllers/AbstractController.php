<?php

namespace Aatis\Core\Controllers;

use Aatis\DependencyInjection\Entity\Container;

abstract class AbstractController
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
