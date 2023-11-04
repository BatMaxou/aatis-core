<?php

namespace App\Service;

use App\Entity\Service;
use App\Entity\Container;

class ContainerBuilder
{
    public static function build(): Container
    {
        $container = new Container();
        $path = ROOT . '../src';
        self::registerFolder($path, $container);

        return $container;
    }

    private static function registerFolder(string $folderPath, Container $container): void
    {
        $folderContent = array_diff(scandir($folderPath), array('..', '.'));

        foreach ($folderContent as $element) {
            $path = $folderPath . '/' . $element;

            if (is_dir($path)) {
                self::registerFolder($path, $container);

                continue;
            }

            self::register($path, $container);
        }
    }

    private static function register(string $filePath, Container $container): void
    {
        if (!str_ends_with($filePath, '.php')) {
            return;
        }

        $namespace = self::transformToNamespace($filePath);

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

    private static function transformToNamespace(string $filePath): string
    {
        $temp = str_replace(ROOT . '../src', 'App', $filePath);
        $temp = str_replace(DIRECTORY_SEPARATOR, '\\', $temp);
        $temp = str_replace('.php', '', $temp);

        return $temp;
    }
}
