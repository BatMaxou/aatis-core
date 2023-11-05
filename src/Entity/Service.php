<?php

namespace Aatis\Core\Entity;

class Service
{
    private ?object $instance = null;
    /**
     * @var array<array{
     *   dependecy?: string,
     *   value?: mixed
     * } $args
     */
    private array $args = [];
    private static Container $container;

    public function __construct(
        private string $class,
    ) {
        $this->class = $class;
    }

    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    /**
     * @param array<array{
     *   dependecy?: string,
     *   value?: mixed
     * } $args
     */
    public function setArgs(array $args): void
    {
        $this->args = $args;
    }

    public function setInstance(object $instance): void
    {
        $this->instance = $instance;
    }

    public function instanciate(): void
    {
        $args = [];

        foreach ($this->getDependencies() as $dependency) {
            if (!self::$container->has($dependency)) {
                if (class_exists($dependency)) {
                    $service = new Service($dependency);
                    self::$container->set($dependency, $service);
                    $service->instanciate();
                } else {
                    throw new \Exception("Class $dependency not found");
                }
            }
            $args[] = self::$container->get($dependency);
        };

        if (!empty($args)) {
            $this->setArgs($args);
        }

        $this->instance = new ($this->class)(...$this->args);
    }

    public function getInstance(): object
    {
        if (self::$container && $this->class === self::$container::class) {
            return self::$container;
        }

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

        if (!$constructor) {
            return $dependencies;
        }

        $parameters = $constructor->getParameters();

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type || !($type instanceof \ReflectionNamedType)) {
                continue;
            }

            if (str_contains($type->getName(), '\\')) {
                $dependencies[] = $type->getName();
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
            'class' => $this->class,
            'args' => $this->args,
        ];
    }
}
