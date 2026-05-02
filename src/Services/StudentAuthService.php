<?php

declare(strict_types=1);

namespace V2\Services;

use V2\Repositories\UserRepository;
use V2\Support\Auth;

final class StudentAuthService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly array $config
    ) {
    }

    public function register(string $displayName, string $email, string $password, string $confirmPassword): array
    {
        $displayName = trim($displayName);
        $email = strtolower(trim($email));

        if ($displayName === '' || $email === '' || $password === '' || $confirmPassword === '') {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Please enter a valid email address.'];
        }

        $expectedDomain = '@' . strtolower((string) ($this->config['student_email_domain'] ?? 'student.newinti.edu.my'));
        if (!str_ends_with($email, $expectedDomain)) {
            return ['success' => false, 'message' => 'Registration is limited to INTI student email addresses.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'message' => 'Password confirmation does not match.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
        }

        if (!preg_match('/[0-9]/', $password)) {
            return ['success' => false, 'message' => 'Password must contain at least one number.'];
        }

        $existing = $this->users->findByEmail($email);
        if ($existing !== null) {
            // Verified accounts are not allowed to be re-registered.
            if ((int) ($existing['is_verified'] ?? 1) === 1) {
                return ['success' => false, 'message' => 'An account with that email already exists.'];
            }
            // Mirror V1: an unverified registration can be replaced (handy if the user
            // mistyped their name/password and wants to start over before verifying).
            $this->users->refreshUnverifiedRegistration(
                (int) $existing['id'],
                $displayName,
                password_hash($password, PASSWORD_DEFAULT)
            );

            return [
                'success' => true,
                'message' => 'Registration updated. Please verify your email.',
                'needs_verification' => true,
                'email' => $email,
            ];
        }

        $this->users->create(
            $displayName,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            (string) ($this->config['defaults']['language'] ?? 'en'),
            0
        );

        return [
            'success' => true,
            'message' => 'Account created. Please verify your email.',
            'needs_verification' => true,
            'email' => $email,
        ];
    }

    public function login(string $email, string $password): array
    {
        $email = strtolower(trim($email));
        $user = $this->users->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }

        if ((int) ($user['is_verified'] ?? 0) !== 1) {
            return [
                'success' => false,
                'message' => 'Please verify your email before signing in.',
                'needs_verification' => true,
                'email' => $email,
            ];
        }

        Auth::loginStudent($user);

        return ['success' => true, 'message' => 'Signed in successfully.'];
    }
}
