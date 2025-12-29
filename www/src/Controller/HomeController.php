<?php
declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Response;
use Twig\Environment;

final class HomeController
{
    public function __construct(private Environment $twig)
    {
    }

    public function home(): Response
    {
        $html = $this->twig->render('home.twig', [
            'title' => 'PHP-LEMP-LAMP-like-Docker-Starter-Stack',
        ]);
        return Response::html($html);
    }

    public function health(): array
    {
        return [
            'ok' => true,
            'service' => 'tiny-php-mvc',
            'php' => PHP_VERSION,
        ];
    }
}
