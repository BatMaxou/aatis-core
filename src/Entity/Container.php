<?php

namespace App\Entity;

use App\Entity\Service;

class Container
{
    /**
     * @var array<string, object> $services
     */
    private array $services = [];

    public function __construct()
    {
        Service::setContainer($this);
    }

    public function get(string $class): object
    {
        return $this->services[$class];
    }

    public function set(string $class, object $service): void
    {
        $this->services[$class] = $service;
    }
}
