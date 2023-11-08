<?php

namespace Aatis\Core\Entity;

class Container
{
    /**
     * @var array<string, Service>
     */
    private array $services = [];

    public function __construct()
    {
        Service::setContainer($this);
    }

    public function get(string $class): object
    {
        return self::class === $class ? $this : $this->services[$class]->getInstance();
    }

    public function has(string $class): bool
    {
        return isset($this->services[$class]);
    }

    public function set(string $class, Service $service): void
    {
        $this->services[$class] = $service;
    }
}
