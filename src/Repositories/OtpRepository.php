<?php

declare(strict_types=1);

namespace V2\Repositories;

use PDO;

final class OtpRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findByUserId(int $userId): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM user_otps WHERE user_id = :user_id LIMIT 1');
        $statement->execute([':user_id' => $userId]);

        return $statement->fetch() ?: null;
    }

    public function upsert(int $userId, string $code, string $expiresAt, string $createdAt): void
    {
        // SQLite's CURRENT_TIMESTAMP records UTC, but the rest of the app runs on
        // Asia/Kuala_Lumpur, so age comparisons via strtotime() would be off by 8h.
        // We pass a PHP-formatted local timestamp instead.
        $statement = $this->pdo->prepare(
            'INSERT INTO user_otps (user_id, otp_code, expires_at, created_at)
             VALUES (:user_id, :otp_code, :expires_at, :created_at)
             ON CONFLICT(user_id) DO UPDATE SET
                otp_code = excluded.otp_code,
                expires_at = excluded.expires_at,
                created_at = excluded.created_at'
        );
        $statement->execute([
            ':user_id' => $userId,
            ':otp_code' => $code,
            ':expires_at' => $expiresAt,
            ':created_at' => $createdAt,
        ]);
    }

    public function deleteForUser(int $userId): void
    {
        $statement = $this->pdo->prepare('DELETE FROM user_otps WHERE user_id = :user_id');
        $statement->execute([':user_id' => $userId]);
    }
}
