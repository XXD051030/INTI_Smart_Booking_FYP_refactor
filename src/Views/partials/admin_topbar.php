<?php declare(strict_types=1); ?>
<?php
    $availableLocales = (array) (config('locales.available') ?? ['en']);
    $localeLabels = (array) (config('locales.labels') ?? []);
    $currentLocaleCode = current_locale();
    $currentScript = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $currentQuery = (string) ($_SERVER['QUERY_STRING'] ?? '');
    $nextTarget = 'admin/' . $currentScript . ($currentQuery !== '' ? '?' . $currentQuery : '');
?>
<div class="admin-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= e(asset_url('images/logo/logowhite.png')) ?>" alt="INTI Logo" class="admin-logo">
                <h3 class="mb-0"><?= e($adminHeaderTitle ?? __('admin_dashboard')) ?></h3>
            </div>
            <div class="d-flex align-items-center">
                <form method="POST" action="<?= e(app_url('langsave.php')) ?>" class="me-3 d-flex align-items-center">
                    <?= csrf_field() ?>
                    <input type="hidden" name="next" value="<?= e($nextTarget) ?>">
                    <label for="adminLangSelect" class="form-label mb-0 me-2 text-white small">
                        <i class="fas fa-globe me-1"></i><?= e(__('admin_lang')) ?>
                    </label>
                    <select id="adminLangSelect" name="language" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <?php foreach ($availableLocales as $loc): ?>
                            <option value="<?= e($loc) ?>"<?= $loc === $currentLocaleCode ? ' selected' : '' ?>>
                                <?= e((string) ($localeLabels[$loc] ?? $loc)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <span class="me-3"><?= e(__('admin_welcome')) ?> <?= e((string) ($currentAdmin['display_name'] ?? 'Admin')) ?></span>
                <a href="<?= e(admin_url('logout.php')) ?>" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i><?= e(__('admin_logout')) ?>
                </a>
            </div>
        </div>
    </div>
</div>
