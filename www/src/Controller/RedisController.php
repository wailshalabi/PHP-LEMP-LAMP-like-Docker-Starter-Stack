<?php
declare(strict_types=1);

namespace App\Controller;

use OpenApi\Annotations as OA;

use Predis\Client;

/**
 * @OA\Get(
 *   path="/redis",
 *   summary="Test Redis connection",
 *   @OA\Response(response=200, description="Redis ok")
 * )
 */
final class RedisController
{
    public function __construct(private Client $redis) {}

    public function test(): array
    {
        $key = 'starter:ping';
        $this->redis->set($key, 'pong');
        $value = $this->redis->get($key);

        return ['redis' => 'ok', 'value' => $value];
    }
}
