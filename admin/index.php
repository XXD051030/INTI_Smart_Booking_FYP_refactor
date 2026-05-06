<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

if (current_admin() !== null) {
    redirect('admin/dashboard.php');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_fail();
    $result = app()->adminAuth()->login((string) ($_POST['username'] ?? ''), (string) ($_POST['password'] ?? ''));
    if ($result['success']) {
        redirect('admin/dashboard.php');
    }
    $error = $result['message'];
}

$message = (string) ($_GET['message'] ?? '');

app()->view()->render('admin/login', [
    'pageTitle' => 'Admin Login - Reservation System',
    'error' => $error,
    'message' => $message,
], 'raw');
