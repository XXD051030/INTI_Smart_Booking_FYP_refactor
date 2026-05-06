<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

verify_csrf_or_fail();

$rl = app()->rateLimiter();
$ipBucket = 'register:ip:' . client_ip();
$retry = $rl->retryAfter($ipBucket, 5, 900);
if ($retry > 0) {
    json_response([
        'success' => false,
        'message' => sprintf('Too many registration attempts. Try again in %d seconds.', $retry),
        'retry_after' => $retry,
    ], 429);
}

$username = (string) ($_POST['username'] ?? '');
$email = (string) ($_POST['email'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$confirmPassword = (string) ($_POST['password_confirmation'] ?? '');

$rl->hit($ipBucket);

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
