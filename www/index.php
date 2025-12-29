<?php

declare(strict_types=1);

define('APP_ROOT', __DIR__);

use App\Infrastructure\App;
use Dotenv\Dotenv;

require APP_ROOT . '/vendor/autoload.php';

$dotenvFile = APP_ROOT . '/.env';
if (is_file($dotenvFile)) {
    Dotenv::createImmutable(APP_ROOT)->safeLoad();
}

$app = new App();
$app->run();