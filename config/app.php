<?php

declare(strict_types=1);

return [
    'name' => 'INTI Booking System V2',
    'timezone' => 'Asia/Kuala_Lumpur',
    'student_email_domain' => 'student.newinti.edu.my',
    'base_url' => null,
    'booking' => [
        'max_request_count_per_day' => 2,
        'max_consecutive_slots' => 2,
        'cancel_buffer_minutes' => 30,
        'slot_length_minutes' => 60,
    ],
    'mail' => (static function () {
        // Read env vars via getenv() so it works regardless of php.ini's
        // variables_order setting (CLI defaults to "GPCS" — no E — on macOS).
        $env = static fn (string $k, string $default = ''): string => (string) (getenv($k) !== false ? getenv($k) : $default);

        // Set MAIL_ENABLED=true (and the SMTP_* vars below) to deliver real mail.
        // When false, sendOtp() and friends only write to log_file; the user-facing
        // flow still succeeds so dev can copy codes out of mail.log.
        return [
            'enabled' => filter_var($env('MAIL_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN),
            'log_file' => __DIR__ . '/../storage/logs/mail.log',
            'smtp' => [
                'host' => $env('SMTP_HOST'),
                'port' => (int) $env('SMTP_PORT', '587'),
                'username' => $env('SMTP_USERNAME'),
                'password' => $env('SMTP_PASSWORD'),
                // 'tls' (STARTTLS on 587) | 'ssl' (implicit TLS on 465) | '' (no encryption)
                'encryption' => $env('SMTP_ENCRYPTION', 'tls'),
                'timeout' => 10,
            ],
            'from' => [
                'address' => $env('MAIL_FROM_ADDRESS', 'no-reply@inti.local'),
                'name' => $env('MAIL_FROM_NAME', 'INTI Smart Booking'),
            ],
        ];
    })(),
    'defaults' => [
        'admin' => [
            'username' => 'admin',
            'display_name' => 'System Admin',
            'email' => 'admin@inti.local',
            'password' => 'admin123',
        ],
        'language' => 'en',
    ],
    'locales' => [
        'available' => ['en', 'ms', 'zh'],
        'labels' => [
            'en' => '🇺🇸 English',
            'ms' => '🇲🇾 Malay (Bahasa Melayu)',
            'zh' => '🇨🇳 Chinese (中文)',
        ],
    ],
];
