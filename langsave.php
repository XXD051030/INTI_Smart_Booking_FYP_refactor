<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = (string) ($_POST['language'] ?? 'en');
    $allowed = ['en', 'ms', 'ta', 'zh'];
    if (!in_array($selected, $allowed, true)) {
        $selected = 'en';
    }
    $_SESSION['language'] = $selected;
    redirect('index.php');
}

redirect('language.php');
