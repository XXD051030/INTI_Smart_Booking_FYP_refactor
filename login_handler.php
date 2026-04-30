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
json_response([
    'success' => $result['success'],
    'message' => $result['success'] ? 'Login successful' : ($result['message'] ?? 'Invalid email or password'),
]);
