<?php

namespace Aatis\Core\Entity;

use Aatis\Core\Entity\Service;

class Container
{
    /**
     * @var array<string, Service> $services
     */
    private array $services = [];

    public function __construct()
    {
        Service::setContainer($this);
    }

    public function get(string $class): object
    {
        return $class === self::class ? $this : $this->services[$class]->getInstance();
    }

    public function has(string $class): bool
    {
        return isset($this->services[$class]);
    }

    public function set(string $class, object $service): void
    {
        $this->services[$class] = $service;
    }
}
