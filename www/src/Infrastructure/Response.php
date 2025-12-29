<?php
declare(strict_types=1);

namespace App\Infrastructure;

final class Response
{
    public function __construct(
        private int $status = 200,
        private array $headers = [],
        private string $body = ''
    ) {
    }

    public static function json(mixed $data, int $status = 200): self
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            $json = '{"error":"json_encode failed"}';
            $status = 500;
        }

        return new self(
            $status,
            ['Content-Type' => 'application/json; charset=utf-8'],
            $json
        );
    }

    public static function html(string $html, int $status = 200): self
    {
        return new self(
            $status,
            ['Content-Type' => 'text/html; charset=utf-8'],
            $html
        );
    }

    /**
     * Send a raw body without encoding (needed for swagger-php ->toJson()).
     */
    public static function raw(string $body, string $contentType = 'text/plain; charset=utf-8', int $status = 200): self
    {
        return new self(
            $status,
            ['Content-Type' => $contentType],
            $body
        );
    }

    public function send(): void
    {
        // If something already printed output (warnings), headers will fail.
        // We still output body to help debugging, but avoid PHP warnings.
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }

        echo $this->body;
    }
}
