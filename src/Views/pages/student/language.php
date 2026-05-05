<?php declare(strict_types=1);
$current = current_locale();
$languageFlash = pull_flash('language');
$availableLocales = (array) (config('locales.available') ?? ['en']);
$labels = (array) (config('locales.labels') ?? []);
?>
<?php if ($languageFlash !== null): ?>
    <div class="alert alert-<?= ($languageFlash['type'] ?? 'success') === 'success' ? 'success' : 'danger' ?> mb-3" role="alert">
        <?= e($languageFlash['message']) ?>
    </div>
<?php endif; ?>
<h3><?= e(__('select_title')) ?></h3>
<form method="post" action="<?= e(app_url('langsave.php')) ?>" class="mt-3" style="max-width: 400px;">
    <div class="form-group mb-3">
        <label for="language"><?= e(__('label')) ?></label>
        <select class="form-control" id="language" name="language">
            <?php foreach ($availableLocales as $locale): ?>
                <option value="<?= e($locale) ?>" <?= $current === $locale ? 'selected' : '' ?>><?= e($labels[$locale] ?? $locale) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><?= e(__('save')) ?></button>
</form>
