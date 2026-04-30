<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

require_student();

$user = app()->users()->findById((int) current_student()['id']);
if ($user === null) {
    \V2\Support\Auth::logoutStudent();
    flash('auth', 'Your session is no longer valid.', 'error');
    redirect('login.php');
}

\V2\Support\Auth::loginStudent($user);

$statusFilter = (string) ($_GET['status'] ?? 'all');
$dateFilter = (string) ($_GET['date'] ?? 'all');
$currentPage = max(1, (int) ($_GET['page'] ?? 1));
$bookingsPerPage = 10;

$allBookings = app()->bookings()->groupedForUser((int) $user['id']);
$today = date('Y-m-d');

$filtered = array_filter($allBookings, static function (array $booking) use ($statusFilter, $dateFilter, $today): bool {
    if ($statusFilter !== 'all' && ($booking['status'] ?? '') !== $statusFilter) {
        return false;
    }
    return match ($dateFilter) {
        'upcoming' => $booking['booking_date'] >= $today,
        'past' => $booking['booking_date'] < $today,
        'today' => $booking['booking_date'] === $today,
        default => true,
    };
});
$filtered = array_values($filtered);

$totalBookings = count($filtered);
$totalPages = (int) max(1, ceil($totalBookings / $bookingsPerPage));
$pageBookings = array_slice($filtered, ($currentPage - 1) * $bookingsPerPage, $bookingsPerPage);

app()->view()->render('student/my_bookings', [
    'pageTitle' => 'My Bookings - INTI Reservation System',
    'headerTitle' => 'My Bookings',
    'activeNav' => 'my_bookings',
    'currentUser' => $user,
    'notificationCount' => app()->notificationService()->unreadCount((int) $user['id']),
    'bookings' => $pageBookings,
    'totalBookings' => $totalBookings,
    'totalPages' => $totalPages,
    'currentPage' => $currentPage,
    'statusFilter' => $statusFilter,
    'dateFilter' => $dateFilter,
    'pageStyles' => ['booking.css'],
    'pageScripts' => ['my_bookings.js'],
], 'student');
