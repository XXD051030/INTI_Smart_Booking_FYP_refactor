<?php declare(strict_types=1);

$messages = [];
foreach (['message', 'auth', 'admin_auth'] as $key) {
    $flash = pull_flash($key);
    if ($flash !== null) {
        $messages[] = $flash;
    }
}
$bootstrapType = static function (string $type): string {
    return match ($type) {
        'error', 'danger' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
        default => 'success',
    };
};
?>
<?php foreach ($messages as $flash): ?>
    <div class="alert alert-<?= e($bootstrapType($flash['type'] ?? 'success')) ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message'] ?? '') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endforeach; ?>
