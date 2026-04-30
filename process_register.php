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
    $_SESSION['email_reg'] = $email;
    json_response(['success' => true, 'message' => 'Registration successful. You can now log in.']);
}

json_response(['success' => false, 'message' => $result['message']]);
