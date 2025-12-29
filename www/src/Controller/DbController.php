<?php
declare(strict_types=1);

namespace App\Controller;

use OpenApi\Annotations as OA;

use Doctrine\DBAL\Connection;

/**
 * @OA\Get(
 *   path="/db",
 *   summary="Test MySQL connection (DBAL)",
 *   @OA\Response(response=200, description="MySQL ok")
 * )
 */
final class DbController
{
    public function __construct(private Connection $db) {}

    public function test(): array
    {
        // Create a tiny table and insert one row
        $this->db->executeStatement(
            "CREATE TABLE IF NOT EXISTS health_check (
                id INT AUTO_INCREMENT PRIMARY KEY,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $this->db->executeStatement("INSERT INTO health_check () VALUES ()");

        $count = (int) $this->db->fetchOne("SELECT COUNT(*) FROM health_check");

        return ['mysql' => 'ok', 'rows_in_health_check' => $count];
    }
}
