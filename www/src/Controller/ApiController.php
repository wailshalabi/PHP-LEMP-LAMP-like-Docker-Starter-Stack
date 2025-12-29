<?php
declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Response;
use OpenApi\Attributes as OA;
use OpenApi\Generator;
use Psr\Log\NullLogger;

#[OA\Info(title: 'PHP-LEMP-LAMP-like-Docker-Starter-Stack API', version: '1.0.0')]
final class ApiController
{
    #[OA\Get(
        path: '/api/hello',
        summary: 'Hello API',
        responses: [
            new OA\Response(response: 200, description: 'Hello')
        ]
    )]
    public function hello(): array
    {
        return ['message' => 'Hello from API', 'time' => date('c')];
    }

    public function openapi(): Response
    {
        // swagger-php expects options array as 2nd argument in your version
        $openapi = Generator::scan(
            [APP_ROOT . '/src'],
            ['logger' => new NullLogger()]
        );

        // toJson() already returns JSON string -> send raw (no json_encode)
        return Response::raw($openapi->toJson(), 'application/json; charset=utf-8');
    }

    public function docs(): Response
    {
        $file = APP_ROOT . '/templates/swagger.twig.html';
        if (!is_file($file)) {
            return Response::json(['error' => 'Swagger template not found'], 500);
        }

        return Response::html((string) file_get_contents($file));
    }
}
