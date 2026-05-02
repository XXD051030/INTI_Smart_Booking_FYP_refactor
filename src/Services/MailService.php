<?php

declare(strict_types=1);

namespace V2\Services;

final class MailService
{
    public function __construct(private readonly array $config)
    {
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->config['enabled'] ?? false);
    }

    public function send(string $to, string $subject, string $body): bool
    {
        $this->log($to, $subject, $body);

        if (!$this->isEnabled()) {
            return true;
        }

        // SMTP transport hook — wire PHPMailer here when credentials are available.
        return true;
    }

    public function sendOtp(string $to, string $code, string $displayName = ''): bool
    {
        $subject = 'Your INTI Smart Booking verification code';
        $greeting = $displayName !== '' ? sprintf('Hello %s,', $displayName) : 'Hello,';
        $body = <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
    <div style="background-color: #f8f9fa; padding: 20px; text-align: center;">
        <h2 style="color: #333;">Verification Code</h2>
    </div>
    <div style="padding: 30px; background-color: white;">
        <p>{$greeting}</p>
        <p>Your verification code is:</p>
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; color: #f61f1f; letter-spacing: 5px; padding: 15px 30px; border: 2px solid #f61f1f; border-radius: 5px;">{$code}</span>
        </div>
        <p>This verification code is valid for 15 minutes. Please use it promptly.</p>
        <p>If you did not request this verification code, please ignore this email.</p>
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        <p style="color: #666; font-size: 14px;">This email was sent automatically by the system. Please do not reply.</p>
    </div>
</div>
HTML;

        return $this->send($to, $subject, $body);
    }

    private function log(string $to, string $subject, string $body): void
    {
        $logFile = (string) ($this->config['log_file'] ?? APP_ROOT . '/storage/logs/mail.log');
        $directory = dirname($logFile);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $payload = sprintf(
            "[%s] TO: %s | SUBJECT: %s\n%s\n\n",
            date('c'),
            $to,
            $subject,
            $body
        );

        file_put_contents($logFile, $payload, FILE_APPEND);
    }
}
