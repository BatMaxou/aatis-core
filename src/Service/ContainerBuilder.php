<?php

namespace Aatis\Core\Service;

use Aatis\Core\Entity\Service;
use Aatis\Core\Entity\Container;
use Symfony\Component\Yaml\Yaml;

/**
 * @phpstan-type ServiceParams array<string, array{
 *  arguments?: array<mixed>,
 *  environment?: array<string>
 * }>
 * @phpstan-type YamlConfig array{
 *  excludes?: array<int, string>,
 *  services?: ServiceParams,
 * }
 * @phpstan-type ComposerJsonConfig array{
 *  autoload: array{
 *     psr-4: array<string, string>
 *  },
 * }
 */
class ContainerBuilder
{
    /**
     * @var array<int, string>
     */
    private array $excludePaths = [];
    /** @var ServiceParams */
    private array $givenParams = [];
    private Container $container;

    /**
     * @param array{
     *  env: string
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

        $folderContent = array_diff(scandir($folderPath) ?: [], ['..', '.']);

        foreach ($folderContent as $element) {
            $path = $folderPath.'/'.$element;

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

        if (isset($this->givenParams[$namespace])) {
            if (
                isset($this->givenParams[$namespace]['environment'])
                && !in_array($this->ctx['env'], $this->givenParams[$namespace]['environment'])
            ) {
                return;
            }

            if (isset($this->givenParams[$namespace]['arguments'])) {
                $service->setGivenArgs($this->givenParams[$namespace]['arguments']);
            }
        }
        $this->container->set($namespace, $service);
    }

    private function getShortPath(string $path): string
    {
        return str_replace($_ENV['DOCUMENT_ROOT'].'/../src', '', $path);
    }

    private function transformToNamespace(string $filePath): string
    {
        /** @var ComposerJsonConfig */
        $composerJson = json_decode(file_get_contents($_ENV['DOCUMENT_ROOT'].'/../composer.json') ?: '', true);
        $autoloaderInfos = $composerJson['autoload']['psr-4'];
        $baseNamespace = array_key_first(array_filter($autoloaderInfos, fn ($value) => 'src/' === $value));
        $temp = str_replace($_ENV['DOCUMENT_ROOT'].'/../src/', $baseNamespace ?? 'App\\', $filePath);
        $temp = str_replace(DIRECTORY_SEPARATOR, '\\', $temp);
        $temp = str_replace('.php', '', $temp);

        return $temp;
    }

    private function getConfig(): void
    {
        if (file_exists($_ENV['DOCUMENT_ROOT'].'/../config/services.yaml')) {
            /** @var YamlConfig */
            $config = Yaml::parseFile($_ENV['DOCUMENT_ROOT'].'/../config/services.yaml');
            $this->excludePaths = $config['excludes'] ?? [];
            $this->givenParams = $config['services'] ?? [];
        }
    }
}
