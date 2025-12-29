<?php
declare(strict_types=1);

use App\Controller\ApiController;
use App\Controller\DbController;
use App\Controller\HomeController;
use App\Controller\MailController;
use App\Controller\RedisController;

$r->addRoute('GET', '/', [HomeController::class, 'home']);
$r->addRoute('GET', '/health', [HomeController::class, 'health']);

$r->addRoute('GET', '/db', [DbController::class, 'test']);
$r->addRoute('GET', '/redis', [RedisController::class, 'test']);
$r->addRoute('GET', '/mail', [MailController::class, 'sendTest']);

$r->addRoute('GET', '/api/hello', [ApiController::class, 'hello']);
$r->addRoute('GET', '/api/openapi.json', [ApiController::class, 'openapi']);
$r->addRoute('GET', '/api/docs', [ApiController::class, 'docs']);
