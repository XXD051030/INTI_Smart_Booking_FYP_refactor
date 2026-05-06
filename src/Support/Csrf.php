<?php

declare(strict_types=1);

namespace V2\Support;

final class Csrf
{
    private const SESSION_KEY = '_csrf';
    private const FIELD_NAME = '_token';
    private const HEADER_NAME = 'HTTP_X_CSRF_TOKEN';

    public static function token(): string
    {
        if (!isset($_SESSION[self::SESSION_KEY]) || !is_string($_SESSION[self::SESSION_KEY])) {
            self::rotate();
        }

        return (string) $_SESSION[self::SESSION_KEY];
    }

    public static function rotate(): void
    {
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
    }

    public static function fieldName(): string
    {
        return self::FIELD_NAME;
    }

    public static function check(?string $candidate): bool
    {
        if ($candidate === null || $candidate === '') {
            return false;
        }
        $expected = $_SESSION[self::SESSION_KEY] ?? null;
        if (!is_string($expected) || $expected === '') {
            return false;
        }

        return hash_equals($expected, $candidate);
    }

    public static function fromRequest(): ?string
    {
        if (isset($_POST[self::FIELD_NAME]) && is_string($_POST[self::FIELD_NAME])) {
            return $_POST[self::FIELD_NAME];
        }
        if (isset($_SERVER[self::HEADER_NAME]) && is_string($_SERVER[self::HEADER_NAME])) {
            return $_SERVER[self::HEADER_NAME];
        }

        return null;
    }
}
