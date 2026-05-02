<?php

declare(strict_types=1);

namespace V2\Services;

use V2\Repositories\OtpRepository;
use V2\Repositories\UserRepository;

final class OtpService
{
    private const TTL_MINUTES = 15;
    private const RESEND_THROTTLE_SECONDS = 60;

    public function __construct(
        private readonly OtpRepository $otps,
        private readonly UserRepository $users,
        private readonly MailService $mail,
    ) {
    }

    public function sendForUser(array $user): array
    {
        $userId = (int) $user['id'];
        $existing = $this->otps->findByUserId($userId);
        if ($existing !== null) {
            $createdAt = strtotime((string) $existing['created_at']);
            if ($createdAt !== false) {
                $age = time() - $createdAt;
                if ($age < self::RESEND_THROTTLE_SECONDS) {
                    return [
                        'success' => false,
                        'message' => sprintf(
                            'Please wait %d more seconds before requesting another code.',
                            self::RESEND_THROTTLE_SECONDS - $age
                        ),
                        'retry_after' => self::RESEND_THROTTLE_SECONDS - $age,
                    ];
                }
            }
        }

        $code = (string) random_int(100000, 999999);
        $now = time();
        $expiresAt = date('Y-m-d H:i:s', $now + self::TTL_MINUTES * 60);
        $createdAt = date('Y-m-d H:i:s', $now);
        $this->otps->upsert($userId, $code, $expiresAt, $createdAt);

        $this->mail->sendOtp((string) $user['email'], $code, (string) $user['display_name']);

        return [
            'success' => true,
            'message' => 'A new verification code has been sent to your email.',
            'expires_at' => $expiresAt,
        ];
    }

    public function verify(int $userId, string $enteredCode): array
    {
        $enteredCode = trim($enteredCode);
        if ($enteredCode === '') {
            return ['success' => false, 'message' => 'Please enter the verification code.'];
        }

        $row = $this->otps->findByUserId($userId);
        if ($row === null) {
            return ['success' => false, 'message' => 'Please send the OTP first before attempting to verify.'];
        }

        if (time() > strtotime((string) $row['expires_at'])) {
            return ['success' => false, 'message' => 'OTP has expired. Please request a new one.'];
        }

        if (!hash_equals((string) $row['otp_code'], $enteredCode)) {
            return ['success' => false, 'message' => 'The OTP you entered is invalid. Kindly try again.'];
        }

        $this->users->markVerified($userId);
        $this->otps->deleteForUser($userId);

        return ['success' => true, 'message' => 'Your account has been successfully verified.'];
    }
}
