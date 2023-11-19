<?php

namespace Aatis\Core\Service;

use Aatis\Core\Entity\Route;
use Aatis\Core\Controllers\AatisController;
use Aatis\DependencyInjection\Entity\Container;

class Router
{
    /**
     * @var Route[]
     */
    private array $routes = [];

    public function __construct(private readonly Container $container, private readonly AatisController $baseController)
    {
        $controllerServices = $this->container->getByTag('controller');
        foreach ($controllerServices as $controllerService) {
            $this->extractRoutes($controllerService->getClass());
        }
    }

    public function redirect(): void
    {
        $explodedUri = $this->explodeUri($_SERVER['REQUEST_URI']);
        $routeInfos = $this->findRoute($explodedUri);

        if ($routeInfos) {
            $route = $routeInfos['route'];
            $params = $routeInfos['params'];

            $namespace = $route->getController();
            if (!$namespace) {
                $this->baseController->problem();

                return;
            }

            $controller = $this->container->get($namespace);
            $controller->{$route->getMethodName()}(...$params);
        } elseif (empty($this->routes)) {
            $this->baseController->home();
        } else {
            header('HTTP/1.0 404 Not Found');
            require_once $_ENV['DOCUMENT_ROOT'] . '/../views/errors/404.php';
        }
    }

    /**
     * @param class-string $controller
     */
    private function extractRoutes(string $controller): void
    {
        $reflection = new \ReflectionClass($controller);
        $methods = $reflection->getMethods();

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(Route::class);

            if (empty($attributes)) {
                continue;
            }

            foreach ($attributes as $attribute) {
                $this->routes[] = (new Route(...$attribute->getArguments()))
                    ->setController($controller)
                    ->setMethodName($method->getName());
            }
        }
    }

    /**
     * @return string[]
     */
    private function explodeUri(string $uri): array
    {
        return explode('/', explode('?', $uri)[0] ?? '');
    }

    /**
     * @param string[] $explodedUri
     *
     * @return array{
     *  route: Route,
     *  params: array<string, string|int>
     * }|null
     */
    private function findRoute(array $explodedUri): ?array
    {
        $foundedRoute = null;
        $params = [];

        foreach ($this->routes as $route) {
            $explodedPath = $this->explodeUri($route->getPath());

            if (count($explodedPath) !== count($explodedUri)) {
                continue;
            }

            for ($i = 0; $i < count($explodedPath); ++$i) {
                if (preg_match('/^{.*}$/', $explodedPath[$i])) {
                    $params[substr($explodedPath[$i], 1, -1)] = $explodedUri[$i];
                    continue;
                }

                if ($explodedPath[$i] !== $explodedUri[$i]) {
                    continue 2;
                }
            }

            $foundedRoute = $route;
        }

        return $foundedRoute ? [
            'route' => $foundedRoute,
            'params' => $params,
        ] : null;
    }
}
