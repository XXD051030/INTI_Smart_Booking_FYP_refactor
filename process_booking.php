<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

$student = require_student_json();
$userId = (int) $student['id'];

$user = app()->users()->findById($userId);
if ($user === null) {
    json_response(['success' => false, 'message' => 'User not found']);
}

$facilityId = (int) ($_POST['facility_id'] ?? 0);
$bookingDate = (string) ($_POST['booking_date'] ?? '');
$purpose = (string) ($_POST['purpose'] ?? '');
$timeSlotsJson = (string) ($_POST['time_slots'] ?? '');
$startTime = (string) ($_POST['start_time'] ?? '');

$timeSlots = [];
if ($timeSlotsJson !== '') {
    $decoded = json_decode($timeSlotsJson, true);
    if (is_array($decoded)) {
        $timeSlots = array_map('strval', $decoded);
    }
}
if ($timeSlots === [] && $startTime !== '') {
    $timeSlots = [$startTime];
}

$result = app()->bookingService()->createStudentBooking(
    $userId,
    $facilityId,
    $bookingDate,
    $timeSlots,
    $purpose,
    (string) $user['email']
);

if (!$result['success']) {
    json_response(['success' => false, 'message' => $result['message']]);
}

$facility = app()->facilities()->findActiveById($facilityId);
$bookingIds = app()->bookings()->bookingIdsForRequestToken((string) $result['request_token']);
$slotCount = count($timeSlots);
$formattedDate = date('l, F j, Y', strtotime($bookingDate));
$firstSlot = $timeSlots[0];
$lastSlotEnd = date('H:i', strtotime(end($timeSlots) . ' +1 hour'));
$timeDisplay = date('g:i A', strtotime($firstSlot)) . ' - ' . date('g:i A', strtotime($lastSlotEnd));
$durationText = $slotCount === 1 ? '(1 hour)' : '(' . $slotCount . ' hours, consecutive slots)';

json_response([
    'success' => true,
    'message' => 'Booking confirmed successfully!',
    'booking_ids' => $bookingIds,
    'primary_booking_id' => $result['booking_id'] ?? ($bookingIds[0] ?? 0),
    'slot_count' => $slotCount,
    'facility_name' => $facility['name'] ?? '',
    'booking_date' => $formattedDate,
    'booking_time' => $timeDisplay . ' ' . $durationText,
]);
