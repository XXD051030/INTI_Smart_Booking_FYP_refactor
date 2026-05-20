<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if (current_student() !== null) {
    redirect('general.php');
}

app()->view()->render('auth/register', [
    'pageTitle' => __('page_title_register'),
], 'raw');
