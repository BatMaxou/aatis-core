<?php

namespace App\Controllers;

use App\Entity\Container;

abstract class AbstractController
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
