<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method']);
}

verify_csrf_or_fail();

$email = (string) ($_POST['email'] ?? '');
$password = (string) ($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    json_response(['success' => false, 'message' => 'Please fill in all fields']);
}

$rl = app()->rateLimiter();
$ipBucket = 'login:ip:' . client_ip();
$emailBucket = 'login:email:' . strtolower($email);
$retry = max(
    $rl->retryAfter($ipBucket, 10, 900),
    $rl->retryAfter($emailBucket, 5, 900),
);
if ($retry > 0) {
    json_response([
        'success' => false,
        'message' => sprintf('Too many login attempts. Try again in %d seconds.', $retry),
        'retry_after' => $retry,
    ], 429);
}

$result = app()->studentAuth()->login($email, $password);

if ($result['success']) {
    $rl->clear($ipBucket);
    $rl->clear($emailBucket);
    json_response(['success' => true, 'message' => 'Login successful']);
}

if (empty($result['needs_verification'])) {
    $rl->hit($ipBucket);
    $rl->hit($emailBucket);
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
