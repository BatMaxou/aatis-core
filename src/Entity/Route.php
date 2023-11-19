<?php

namespace Aatis\Core\Entity;

use Attribute;

#[Attribute]
class Route
{
    /**
     * @var class-string|null
     */
    private ?string $controller;

    private ?string $methodName;

    /**
     * @param string[] $args
     */
    public function __construct(private string $path, private array $methods = [])
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return class-string|null
     */
    public function getController(): ?string
    {
        return $this->controller;
    }

    public function getMethodName(): ?string
    {
        return $this->methodName;
    }

    public function setMethodName(string $methodName): static
    {
        $this->methodName = $methodName;

        return $this;
    }

    /**
     * @param class-string $controller
     */
    public function setController(string $controller): static
    {
        $this->controller = $controller;

        return $this;
    }
}
