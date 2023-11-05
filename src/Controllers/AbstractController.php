<?php

namespace Aatis\Core\Controllers;

use Aatis\Core\Entity\Container;

abstract class AbstractController
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
