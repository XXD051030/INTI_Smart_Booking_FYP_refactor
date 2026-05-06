<?php

declare(strict_types=1);

namespace V2\Support;

use PDO;

final class RateLimiter
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Returns the seconds until the bucket cools down, or 0 if the request can proceed.
     * Does NOT record the attempt — call hit() after the action to count it.
     */
    public function retryAfter(string $bucket, int $maxAttempts, int $windowSeconds): int
    {
        $cutoff = $this->cutoff($windowSeconds);
        $this->purgeBefore($cutoff);

        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) AS attempts, MIN(hit_at) AS oldest
             FROM rate_limits
             WHERE bucket = :bucket AND hit_at >= :cutoff'
        );
        $statement->execute([':bucket' => $bucket, ':cutoff' => $cutoff]);
        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: [];
        $attempts = (int) ($row['attempts'] ?? 0);
        if ($attempts < $maxAttempts) {
            return 0;
        }

        $oldest = (string) ($row['oldest'] ?? '');
        $oldestTs = $oldest !== '' ? strtotime($oldest) : false;
        if ($oldestTs === false) {
            return $windowSeconds;
        }

        $secondsLeft = $windowSeconds - (time() - $oldestTs);

        return $secondsLeft > 0 ? $secondsLeft : 0;
    }

    public function hit(string $bucket): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO rate_limits (bucket, hit_at) VALUES (:bucket, :hit_at)'
        );
        $statement->execute([
            ':bucket' => $bucket,
            ':hit_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function clear(string $bucket): void
    {
        $statement = $this->pdo->prepare('DELETE FROM rate_limits WHERE bucket = :bucket');
        $statement->execute([':bucket' => $bucket]);
    }

    private function cutoff(int $windowSeconds): string
    {
        return date('Y-m-d H:i:s', time() - $windowSeconds);
    }

    private function purgeBefore(string $cutoff): void
    {
        // Lazy cleanup: drop rows older than the longest window we ever query.
        // Anything older than 1 hour is fine to forget across all our buckets.
        $statement = $this->pdo->prepare('DELETE FROM rate_limits WHERE hit_at < :cutoff');
        $statement->execute([':cutoff' => date('Y-m-d H:i:s', time() - 3600)]);
    }
}
