<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if (current_student() !== null) {
    redirect('general.php');
}

$email = isset($_SESSION['email_reg']) ? (string) $_SESSION['email_reg'] : '';
if ($email === '') {
    flash('register', 'Your verification session has expired. Please register again.', 'error');
    redirect('register.php');
}

$user = app()->users()->findByEmail($email);
if ($user === null) {
    unset($_SESSION['email_reg']);
    flash('register', 'We could not find your registration. Please try again.', 'error');
    redirect('register.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_fail();
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'sended') {
        $result = app()->otpService()->sendForUser($user);
        $_SESSION['msg'] = $result['message'];
        $_SESSION['msg_type'] = $result['success'] ? 'success' : 'error';
        redirect('otp-verify.php');
    }

    if ($action === 'verify_otp') {
        $entered = (string) ($_POST['otp'] ?? '');
        $result = app()->otpService()->verify((int) $user['id'], $entered);

        if ($result['success']) {
            unset($_SESSION['email_reg']);
            flash('login', 'Your account has been verified. You can now sign in.', 'success');
            redirect('login.php');
        }

        $_SESSION['msg'] = $result['message'];
        $_SESSION['msg_type'] = 'error';
        redirect('otp-verify.php');
    }
}

$msg = $_SESSION['msg'] ?? '';
$msgType = $_SESSION['msg_type'] ?? 'error';
unset($_SESSION['msg'], $_SESSION['msg_type']);

app()->view()->render('auth/otp-verify', [
    'pageTitle' => 'Verify your email - Reservation System',
    'email' => $email,
    'msg' => (string) $msg,
    'msgType' => (string) $msgType,
], 'raw');
