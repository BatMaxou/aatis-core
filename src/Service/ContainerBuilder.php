<?php

namespace Aatis\Core\Service;

use Aatis\Core\Entity\Service;
use Aatis\Core\Entity\Container;

class ContainerBuilder
{

    /**
     * @param array{
     *  env: string,
     *  debug: bool,
     * } $ctx
     */
    public function __construct(
        private readonly array $ctx,
        private readonly string $sourcePath
    ) {
    }

    public function build(): Container
    {
        $container = new Container();
        dd('ok');
        $this->registerFolder($this->sourcePath, $container);

        return $container;
    }

    private function registerFolder(string $folderPath, Container $container): void
    {
        $folderContent = array_diff(scandir($folderPath), array('..', '.'));

        foreach ($folderContent as $element) {
            $path = $folderPath . '/' . $element;

            if (is_dir($path)) {
                $this->registerFolder($path, $container);

                continue;
            }

            $this->register($path, $container);
        }
    }

    private function register(string $filePath, Container $container): void
    {
        if (!str_ends_with($filePath, '.php')) {
            return;
        }

        $namespace = $this->transformToNamespace($filePath);

        if (!class_exists($namespace)) {
            return;
        }

        $reflexion = new \ReflectionClass($namespace);

        if (!$reflexion->isSubclassOf(Service::class)) {
            return;
        }

        $service = new $namespace();

        if (!$service instanceof Service) {
            return;
        }

        $service->instanciate();

        $container->set($namespace, $service->getInstance());
    }

    private function transformToNamespace(string $filePath): string
    {
        $temp = str_replace(ROOT . '../src', 'App', $filePath);
        $temp = str_replace(DIRECTORY_SEPARATOR, '\\', $temp);
        $temp = str_replace('.php', '', $temp);

        return $temp;
    }
}
