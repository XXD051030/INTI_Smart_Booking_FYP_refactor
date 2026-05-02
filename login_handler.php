<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

$email = (string) ($_POST['email'] ?? '');
$password = (string) ($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    json_response(['success' => false, 'message' => 'Please fill in all fields']);
}

$result = app()->studentAuth()->login($email, $password);

if ($result['success']) {
    json_response(['success' => true, 'message' => 'Login successful']);
}

if (!empty($result['needs_verification'])) {
    $_SESSION['email_reg'] = (string) ($result['email'] ?? $email);
    json_response([
        'success' => false,
        'message' => $result['message'] ?? 'Please verify your email before signing in.',
        'redirect_to' => app_url('otp-verify.php'),
    ]);
}

json_response([
    'success' => false,
    'message' => $result['message'] ?? 'Invalid email or password',
]);
