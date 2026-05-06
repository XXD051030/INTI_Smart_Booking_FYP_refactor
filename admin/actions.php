<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

if (current_admin() === null) {
    json_response(['success' => false, 'message' => 'Unauthorized'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

verify_csrf_or_fail();

$action = (string) ($_POST['action'] ?? '');

switch ($action) {
    case 'delete_user':
        $userId = (int) ($_POST['user_id'] ?? 0);
        if ($userId <= 0) {
            json_response(['success' => false, 'message' => 'Invalid user ID']);
        }
        app()->users()->delete($userId);
        json_response(['success' => true, 'message' => 'User deleted']);

    case 'edit_user':
        $userId = (int) ($_POST['user_id'] ?? 0);
        $username = (string) ($_POST['username'] ?? '');
        $email = (string) ($_POST['email'] ?? '');
        if ($userId <= 0 || $username === '' || $email === '') {
            json_response(['success' => false, 'message' => 'All fields are required']);
        }
        $user = app()->users()->findById($userId);
        if ($user === null) {
            json_response(['success' => false, 'message' => 'User not found']);
        }
        app()->users()->updateProfile($userId, $username, $email, (string) ($user['preferred_language'] ?? 'en'));
        json_response(['success' => true, 'message' => 'User updated']);

    case 'reset_password':
        $userId = (int) ($_POST['user_id'] ?? 0);
        $newPassword = (string) ($_POST['new_password'] ?? '');
        if ($userId <= 0 || strlen($newPassword) < 6) {
            json_response(['success' => false, 'message' => 'Password must be at least 6 characters']);
        }
        app()->users()->resetPassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));
        json_response(['success' => true, 'message' => 'Password reset']);

    case 'cancel_booking':
        $bookingId = (int) ($_POST['booking_id'] ?? 0);
        if ($bookingId <= 0) {
            json_response(['success' => false, 'message' => 'Invalid booking ID']);
        }
        $token = app()->bookings()->findRequestTokenByBookingId($bookingId);
        if ($token === null) {
            json_response(['success' => false, 'message' => 'Booking not found']);
        }
        $result = app()->bookingService()->cancelAdminRequest($token);
        json_response(['success' => (bool) $result['success'], 'message' => (string) $result['message']]);

    case 'verify_user':
    case 'delete_otp':
    case 'bulk_delete_expired_otps':
        json_response(['success' => true, 'message' => 'No-op (deferred in V2)']);

    default:
        json_response(['success' => false, 'message' => 'Unknown action']);
}
