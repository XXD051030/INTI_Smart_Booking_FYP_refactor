<?php

declare(strict_types=1);

define('APP_ROOT', __DIR__);

$config = require APP_ROOT . '/config/app.php';

date_default_timezone_set((string) ($config['timezone'] ?? 'Asia/Kuala_Lumpur'));

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$configuredBase = trim((string) ($config['base_url'] ?? ''), '/');

if ($configuredBase !== '') {
    $appBaseUrl = '/' . $configuredBase;
} else {
    $marker = '/v2/';
    $position = strpos($scriptName, $marker);
    if ($position !== false) {
        $appBaseUrl = substr($scriptName, 0, $position + 3);
    } else {
        $scriptDir = str_replace('\\', '/', dirname($scriptName));
        $entryScript = isset($_SERVER['SCRIPT_FILENAME']) ? realpath((string) $_SERVER['SCRIPT_FILENAME']) : false;
        if ($entryScript !== false) {
            $entryDir = str_replace('\\', '/', dirname($entryScript));
            $appRootDir = str_replace('\\', '/', APP_ROOT);
            $relative = str_starts_with($entryDir, $appRootDir) ? substr($entryDir, strlen($appRootDir)) : '';
            if ($relative !== '' && $relative !== '/' && str_ends_with($scriptDir, $relative)) {
                $scriptDir = substr($scriptDir, 0, -strlen($relative));
            }
        }
        $appBaseUrl = ($scriptDir === '' || $scriptDir === '/') ? '' : rtrim($scriptDir, '/');
    }
}

define('APP_BASE_URL', $appBaseUrl === '/' ? '' : $appBaseUrl);
define('PROJECT_BASE_URL', preg_replace('#/v2$#', '', APP_BASE_URL) ?: '');

$GLOBALS['v2_config'] = $config;

spl_autoload_register(static function (string $class): void {
    $prefix = 'V2\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = APP_ROOT . '/src/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($path)) {
        require_once $path;
    }
});

spl_autoload_register(static function (string $class): void {
    $prefix = 'PHPMailer\\PHPMailer\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = APP_ROOT . '/lib/PHPMailer/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($path)) {
        require_once $path;
    }
});

require_once APP_ROOT . '/src/Support/helpers.php';

$GLOBALS['v2_app'] = V2\Support\AppContext::boot($config);
