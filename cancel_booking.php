<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

verify_csrf_or_fail();

$student = require_student_json();
$userId = (int) $student['id'];

$user = app()->users()->findById($userId);
if ($user === null) {
    json_response(['success' => false, 'message' => 'User not found']);
}

$bookingId = (int) ($_POST['booking_id'] ?? 0);
if ($bookingId === 0) {
    json_response(['success' => false, 'message' => 'Booking ID is required']);
}

$requestToken = app()->bookings()->findRequestTokenByBookingId($bookingId, $userId);
if ($requestToken === null) {
    json_response(['success' => false, 'message' => 'Booking not found or access denied']);
}

$booking = app()->bookings()->findGroupedByToken($requestToken, $userId);
$result = app()->bookingService()->cancelStudentRequest($requestToken, $userId, (string) $user['email']);

if (!$result['success']) {
    json_response(['success' => false, 'message' => $result['message']]);
}

$formattedDate = date('l, F j, Y', strtotime((string) $booking['booking_date']));
$formattedStart = date('g:i A', strtotime((string) $booking['start_time']));
$formattedEnd = date('g:i A', strtotime((string) $booking['end_time']));

json_response([
    'success' => true,
    'message' => 'Booking cancelled successfully',
    'booking_id' => $bookingId,
    'facility_name' => (string) $booking['facility_name'],
    'booking_date' => $formattedDate,
    'booking_time' => $formattedStart . ' - ' . $formattedEnd,
]);
