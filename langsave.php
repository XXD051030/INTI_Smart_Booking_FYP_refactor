<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_or_fail();
    $available = (array) (config('locales.available') ?? ['en']);
    $selected = (string) ($_POST['language'] ?? 'en');
    if (!in_array($selected, $available, true)) {
        $selected = (string) (config('defaults.language') ?? 'en');
    }
    $_SESSION['language'] = $selected;
    flash('language', __('save') . ' ✓');
}

$next = (string) ($_POST['next'] ?? $_GET['next'] ?? '');
if ($next !== '' && preg_match('#^[A-Za-z0-9_\-/\.]+\.php(\?[^\s]*)?$#', $next) === 1) {
    redirect($next);
}

redirect('language.php');
