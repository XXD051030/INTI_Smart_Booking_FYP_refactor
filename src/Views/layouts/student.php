<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="<?= e(current_locale()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e($pageTitle ?? 'Reservation Dashboard') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= e(asset_url('css/style.css')) ?>">
    <?php foreach (($pageStyles ?? []) as $style): ?>
        <link rel="stylesheet" href="<?= e(asset_url('css/' . $style)) ?>">
    <?php endforeach; ?>
    <?php foreach (($pageHeadAssets ?? []) as $asset): ?>
        <?= $asset ?>
    <?php endforeach; ?>
</head>
<body>
    <div class="container-fluid p-0">
        <?php include APP_ROOT . '/src/Views/partials/student_topbar.php'; ?>
        <div class="row g-0">
            <?php include APP_ROOT . '/src/Views/partials/student_sidebar.php'; ?>
            <div class="col-md-9 col-lg-10 p-4">
                <?php include APP_ROOT . '/src/Views/partials/alerts.php'; ?>
                <?= $content ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(asset_url('js/notifications.js')) ?>"></script>
    <?php foreach (($pageScripts ?? []) as $script): ?>
        <script src="<?= e(asset_url('js/' . $script)) ?>"></script>
    <?php endforeach; ?>
    <?php foreach (($pageInlineScripts ?? []) as $script): ?>
        <script><?= $script ?></script>
    <?php endforeach; ?>
</body>
</html>
