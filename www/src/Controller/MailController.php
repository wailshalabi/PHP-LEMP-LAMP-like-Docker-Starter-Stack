<?php
declare(strict_types=1);

namespace App\Controller;

use OpenApi\Annotations as OA;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

/**
 * @OA\Get(
 *   path="/mail",
 *   summary="Send a test email (captured by Mailpit)",
 *   @OA\Response(response=200, description="Mail sent")
 * )
 */
final class MailController
{
    public function __construct(private Mailer $mailer)
    {
    }

    public function sendTest(): array
    {
        $from = getenv('MAIL_FROM') ?: 'dev@example.test';
        $to = getenv('MAIL_TO') ?: 'you@example.test';

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject('Test email from PHP Docker Starter')
            ->text("If you're seeing this in Mailpit, SMTP is working.\n\nTime: " . date('c'));

        $this->mailer->send($email);

        return [
            'mail' => 'sent',
            'from' => $from,
            'to' => $to,
            'mailpit_ui' => sprintf('http://localhost:%s', getenv('HOST_MACHINE_MAILPIT_HTTP_PORT') ?: '8025'),
        ];
    }
}
