<?php
declare(strict_types=1);

namespace App\Infrastructure;

use DI\Container;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

final class App
{
    private Container $container;
    private Dispatcher $dispatcher;

    public function __construct()
    {
        $this->container = $this->buildContainer();
        $this->dispatcher = $this->buildRoutes();
    }

    public function run(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $this->json(['error' => 'Not Found'], 404);
                return;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $this->json(['error' => 'Method Not Allowed'], 405);
                return;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$class, $method] = $handler;
                $controller = $this->container->get($class);

                $result = $controller->$method(...array_values($vars));
                $this->respond($result);
                return;
        }
    }

    private function respond(mixed $result): void
    {
        // If controller already handled output
        if ($result === null) {
            return;
        }

        // Simple conventions
        if ($result instanceof Response) {
            $result->send();
            return;
        }

        // Default: JSON
        $this->json($result);
    }

    private function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function buildContainer(): Container
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->useAttributes(true);

        $builder->addDefinitions(Definitions::all());

        return $builder->build();
    }

    private function buildRoutes(): Dispatcher
    {
        return simpleDispatcher(function ($r) {
            /** @var \FastRoute\RouteCollector $r */
            require APP_ROOT . '/route/routes.php';
        });
    }
}
