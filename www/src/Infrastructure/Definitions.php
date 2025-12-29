<?php
declare(strict_types=1);

namespace App\Infrastructure;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Predis\Client as PredisClient;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class Definitions
{
    public static function all(): array
    {
        return [
            Connection::class => static function (): Connection {
                $host = getenv('DB_HOST') ?: 'database';
                $port = (int) (getenv('DB_PORT') ?: 3306);
                $name = getenv('DB_NAME') ?: (getenv('MYSQL_DATABASE') ?: 'app');
                $user = getenv('DB_USER') ?: (getenv('MYSQL_USER') ?: 'app');
                $pass = getenv('DB_PASS') ?: (getenv('MYSQL_PASSWORD') ?: 'app');

                return DriverManager::getConnection([
                    'driver' => 'pdo_mysql',
                    'host' => $host,
                    'port' => $port,
                    'dbname' => $name,
                    'user' => $user,
                    'password' => $pass,
                    'charset' => 'utf8mb4',
                ]);
            },

            PredisClient::class => static function (): PredisClient {
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = (int) (getenv('REDIS_PORT') ?: 6379);

                return new PredisClient([
                    'scheme' => 'tcp',
                    'host' => $host,
                    'port' => $port,
                ]);
            },

            Environment::class => static function (): Environment {
                $loader = new FilesystemLoader(APP_ROOT . '/templates');
                $twig = new Environment($loader, [
                    'cache' => false,
                    'auto_reload' => true,
                ]);
                return $twig;
            },

            Mailer::class => static function (): Mailer {
                $dsn = getenv('MAILER_DSN') ?: 'smtp://mailpit:1025';
                $transport = Transport::fromDsn($dsn);
                return new Mailer($transport);
            },
        ];
    }
}
