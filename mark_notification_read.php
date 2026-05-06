<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

verify_csrf_or_fail();

$student = require_student_json();
$userId = (int) $student['id'];

$markAll = isset($_POST['mark_all']) && $_POST['mark_all'] === 'true';
$notificationId = (int) ($_POST['notification_id'] ?? 0);

if ($markAll) {
    app()->notificationService()->markAllRead($userId);
    json_response(['success' => true, 'message' => 'All notifications marked as read']);
}

if ($notificationId <= 0) {
    json_response(['success' => false, 'message' => 'Invalid notification ID']);
}

app()->notificationService()->markRead($notificationId, $userId);
json_response(['success' => true, 'message' => 'Notification marked as read']);
