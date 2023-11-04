<?php

namespace App\Entity;

use App\Entity\Container;

class Service
{
    private ?object $instance = null;

    private static ?Container $container = null;

    /**
     * @param array<array{
     *   dependecy?: string,
     *   value?: mixed
     * } $args
     */
    public function __construct(
        private string $name,
        private string $class,
        private array $args = []
    ) {
        $this->name = $name;
        $this->class = $class;
        $this->args = $args;
    }

    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    public function instanciate(): void
    {
        $this->instance = (new $this->class)(...$this->args);
        self::$container->set($this->name, $this->instance);
    }

    public function getInstance(): object
    {
        if (!$this->instance) {
            $this->instanciate();
        }

        return $this->instance;
    }

    /**
     * @return String[]
     */
    public function getDependencies(): array
    {
        $dependencies = [];
        $reflexion = new \ReflectionClass($this->class);
        $constructor = $reflexion->getConstructor();
        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $type = $parameter->getType()->getName();
            if (str_contains($type, '\\')) {
                $dependencies[] = $type;
            }
        }

        return $dependencies;
    }

    public function isInstancied(): bool
    {
        return $this->instance ? true : false;
    }


    /**
     * @return array{
     *  name: string,
     *  class: string,
     *  args: array<array{
     *      dependecy?: string,
     *      value?: mixed
     *  }>
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'class' => $this->class,
            'args' => $this->args,
        ];
    }
}
