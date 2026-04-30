<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

require_admin();

$currentAdmin = current_admin();

$users = app()->users()->all();
$totalUsers = count($users);

app()->view()->render('admin/dashboard', [
    'pageTitle' => 'Admin Dashboard - Reservation System',
    'adminHeaderTitle' => 'Admin Dashboard',
    'activeNav' => 'dashboard',
    'currentAdmin' => $currentAdmin,
    'users' => $users,
    'stats' => [
        'total_users' => $totalUsers,
        'verified_users' => $totalUsers,
        'unverified_users' => 0,
    ],
    'otps' => [],
    'otpStats' => ['active_otps' => 0],
], 'admin');
