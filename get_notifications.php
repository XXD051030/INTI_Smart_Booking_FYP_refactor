<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$student = require_student_json();
$userId = (int) $student['id'];

$limit = (int) ($_GET['limit'] ?? 10);
$unreadOnly = isset($_GET['unread_only']) && $_GET['unread_only'] === 'true';

if ($limit < 1 || $limit > 50) {
    $limit = 10;
}

$icons = [
    'booking_confirmed' => ['icon' => 'fas fa-check-circle', 'color' => 'success'],
    'booking_cancelled' => ['icon' => 'fas fa-times-circle', 'color' => 'danger'],
    'booking_reminder' => ['icon' => 'fas fa-clock', 'color' => 'warning'],
    'system_notice' => ['icon' => 'fas fa-info-circle', 'color' => 'info'],
];
$defaultIcon = ['icon' => 'fas fa-bell', 'color' => 'primary'];

$formatTime = static function (string $datetime): string {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) {
        return 'Just now';
    }
    if ($diff < 3600) {
        $minutes = (int) floor($diff / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    }
    if ($diff < 86400) {
        $hours = (int) floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    }
    if ($diff < 2592000) {
        $days = (int) floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }
    return date('M j, Y', $time);
};

$rows = app()->notificationService()->listForUser($userId, $unreadOnly);
$rows = array_slice($rows, 0, $limit);
$formatted = [];
foreach ($rows as $row) {
    $type = (string) $row['type'];
    $iconInfo = $icons[$type] ?? $defaultIcon;
    $formatted[] = [
        'id' => (int) $row['id'],
        'type' => $type,
        'title' => (string) $row['title'],
        'message' => (string) $row['message'],
        'related_booking_id' => $row['related_booking_id'] !== null ? (int) $row['related_booking_id'] : null,
        'is_read' => (bool) $row['is_read'],
        'created_at' => (string) $row['created_at'],
        'read_at' => $row['read_at'],
        'time_formatted' => $formatTime((string) $row['created_at']),
        'icon' => $iconInfo['icon'],
        'color' => $iconInfo['color'],
    ];
}

json_response([
    'success' => true,
    'notifications' => $formatted,
    'unread_count' => app()->notificationService()->unreadCount($userId),
    'total_count' => count($formatted),
]);
