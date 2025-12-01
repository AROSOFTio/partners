<?php
class Mailer
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function send(string $to, string $subject, string $body, ?string $fromEmail = null, ?string $fromName = null): bool
    {
        $fromEmail = $fromEmail ?: ($this->config['from_email'] ?? 'no-reply@example.com');
        $fromName = $fromName ?: ($this->config['from_name'] ?? 'BenTech Collaborations');
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';

        // For SMTP, integrate PHPMailer and configure host/port/credentials here.
        return mail($to, $subject, $body, implode("\r\n", $headers));
    }
}
