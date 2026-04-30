<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

header('Content-Type: application/json');

$facilityId = (int) ($_GET['facility_id'] ?? 0);
if ($facilityId === 0) {
    echo json_encode([]);
    exit;
}

$rows = app()->bookings()->listConfirmedEvents($facilityId);
$events = [];
foreach ($rows as $row) {
    $events[] = [
        'id' => (int) $row['booking_id'],
        'title' => (string) $row['facility_name'],
        'start' => $row['booking_date'] . 'T' . $row['start_time'],
        'end' => $row['booking_date'] . 'T' . $row['end_time'],
    ];
}

echo json_encode($events);
