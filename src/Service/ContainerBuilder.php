<?php

namespace Aatis\Core\Service;

use Aatis\Core\Entity\Service;
use Aatis\Core\Entity\Container;
use Symfony\Component\Yaml\Yaml;

class ContainerBuilder
{
    /**
     * @var String[]
     */
    private array $excludePaths = [];
    /**
     * @var array<string, mixed>
     */
    private array $givenArgs = [];
    private Container $container;

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
        $this->getConfig();
    }

    public function build(): Container
    {
        $this->container = new Container();
        $this->registerFolder($this->sourcePath);

        return $this->container;
    }

    private function registerFolder(string $folderPath): void
    {
        if (in_array($this->getShortPath($folderPath), $this->excludePaths)) {
            return;
        }

        $folderContent = array_diff(scandir($folderPath), array('..', '.'));

        foreach ($folderContent as $element) {
            $path = $folderPath . '/' . $element;

            if (is_dir($path)) {
                $this->registerFolder($path);

                continue;
            }

            $this->register($path);
        }
    }

    private function register(string $filePath): void
    {
        $shortPath = $this->getShortPath($filePath);

        if (
            !str_ends_with($shortPath, '.php')
            || in_array($shortPath, $this->excludePaths)
            || str_ends_with($shortPath, 'Interface.php')
        ) {
            return;
        }

        $namespace = $this->transformToNamespace($filePath);

        if (
            !class_exists($namespace)
            || (new \ReflectionClass($namespace))->isAbstract()
        ) {
            return;
        }
        $service = new Service($namespace);
        if (isset($this->givenArgs[$namespace]) && isset($this->givenArgs[$namespace]['arguments'])) {
            $service->setGivenArgs($this->givenArgs[$namespace]['arguments']);
        }
        $this->container->set($namespace, $service);
    }

    private function getShortPath(string $path): string
    {
        return str_replace(ROOT . '../src', '', $path);
    }

    private function transformToNamespace(string $filePath, $isVendor = false): string
    {
        $autoloaderInfos = json_decode(file_get_contents(ROOT . '../composer.json'), true)['autoload']['psr-4'];
        $baseNamespace = array_key_first(array_filter($autoloaderInfos, fn ($value) => $value === 'src/'));
        $temp = str_replace(ROOT . '../src/', $baseNamespace, $filePath);
        $temp = str_replace(DIRECTORY_SEPARATOR, '\\', $temp);
        $temp = str_replace('.php', '', $temp);

        return $temp;
    }

    private function getConfig(): void
    {
        if (file_exists(ROOT . '../config/services.yaml')) {
            $config = Yaml::parseFile(ROOT . '../config/services.yaml');
            $this->excludePaths = $config['excludes'] ?? [];
            $this->givenArgs = $config['services'] ?? [];
        }
    }
}
