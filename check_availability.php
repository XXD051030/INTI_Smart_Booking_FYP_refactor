<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$student = require_student_json();
$userId = (int) $student['id'];
$action = (string) ($_POST['action'] ?? 'check_availability');

if ($action === 'check_daily_count') {
    $date = (string) ($_POST['date'] ?? '');
    if ($date === '') {
        json_response(['success' => false, 'message' => 'Date is required']);
    }
    $count = app()->bookings()->countRequestTokensForUserOnDate($userId, $date);
    json_response(['success' => true, 'count' => $count]);
}

$facilityId = (int) ($_POST['facility_id'] ?? 0);
$date = (string) ($_POST['date'] ?? '');

if ($facilityId === 0 || $date === '') {
    json_response(['success' => false, 'message' => 'Facility ID and date are required']);
}

$facility = app()->facilities()->findActiveById($facilityId);
if ($facility === null) {
    json_response(['success' => false, 'message' => 'Facility not found or inactive']);
}

$today = date('Y-m-d');
if ($date < $today) {
    json_response(['success' => false, 'message' => 'Cannot book for past dates']);
}

$maxDate = date('Y-m-d', strtotime($today . ' + ' . (int) $facility['advance_booking_days'] . ' days'));
if ($date > $maxDate) {
    json_response(['success' => false, 'message' => 'Date exceeds advance booking limit']);
}

$slots = app()->bookingService()->availability($facilityId, $date);
$availableSlots = [];
foreach ($slots as $slot) {
    if ($date === $today && $slot['start_time'] <= date('H:i')) {
        continue;
    }
    $availableSlots[] = [
        'time' => $slot['start_time'],
        'available' => $slot['available'],
    ];
}

json_response([
    'success' => true,
    'available_slots' => $availableSlots,
    'max_date' => $maxDate,
]);
