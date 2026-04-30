<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

require_admin();

$currentAdmin = current_admin();
$facilities = app()->facilities()->allActive();
$timeSlots = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
$today = date('Y-m-d');

app()->view()->render('admin/bookings', [
    'pageTitle' => 'Booking Management - Admin Dashboard',
    'adminHeaderTitle' => 'Booking Management',
    'activeNav' => 'bookings',
    'currentAdmin' => $currentAdmin,
    'facilities' => $facilities,
    'timeSlots' => $timeSlots,
    'today' => $today,
    'pageStyles' => ['admin-bookings.css'],
], 'admin');
