<?php

declare(strict_types=1);

namespace V2\Repositories;

use PDO;

final class NotificationRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function create(int $userId, string $type, string $title, string $message, ?int $relatedBookingId, string $createdAt): void
    {
        // SQLite's CURRENT_TIMESTAMP is UTC; the rest of the app runs on
        // Asia/Kuala_Lumpur, so time_ago() saw newly created rows as 8h in
        // the future. Caller passes a PHP-formatted local timestamp instead.
        $statement = $this->pdo->prepare(
            'INSERT INTO notifications (user_id, type, title, message, related_booking_id, created_at)
             VALUES (:user_id, :type, :title, :message, :related_booking_id, :created_at)'
        );
        $statement->execute([
            ':user_id' => $userId,
            ':type' => $type,
            ':title' => $title,
            ':message' => $message,
            ':related_booking_id' => $relatedBookingId,
            ':created_at' => $createdAt,
        ]);
    }

    public function unreadCount(int $userId): int
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0');
        $statement->execute([':user_id' => $userId]);

        return (int) $statement->fetchColumn();
    }

    public function listForUser(int $userId, bool $onlyUnread = false): array
    {
        $sql = 'SELECT * FROM notifications WHERE user_id = :user_id';
        if ($onlyUnread) {
            $sql .= ' AND is_read = 0';
        }
        $sql .= ' ORDER BY created_at DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':user_id' => $userId]);

        return $statement->fetchAll();
    }

    public function markRead(int $notificationId, int $userId, string $readAt): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE notifications SET is_read = 1, read_at = :read_at WHERE id = :id AND user_id = :user_id'
        );
        $statement->execute([
            ':id' => $notificationId,
            ':user_id' => $userId,
            ':read_at' => $readAt,
        ]);
    }

    public function markAllRead(int $userId, string $readAt): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE notifications SET is_read = 1, read_at = :read_at WHERE user_id = :user_id AND is_read = 0'
        );
        $statement->execute([
            ':user_id' => $userId,
            ':read_at' => $readAt,
        ]);
    }
}
