<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

$username = (string) ($_POST['username'] ?? '');
$email = (string) ($_POST['email'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$confirmPassword = (string) ($_POST['password_confirmation'] ?? '');

$result = app()->studentAuth()->register($username, $email, $password, $confirmPassword);

if ($result['success']) {
    $_SESSION['email_reg'] = (string) ($result['email'] ?? $email);
    $payload = [
        'success' => true,
        'message' => $result['message'] ?? 'Registration successful.',
    ];
    if (!empty($result['needs_verification'])) {
        $payload['redirect_to'] = app_url('otp-verify.php');
    }
    json_response($payload);
}

json_response(['success' => false, 'message' => $result['message']]);
