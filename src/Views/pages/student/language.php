<?php declare(strict_types=1);
$current = $_SESSION['language'] ?? 'en';
?>
<h3>Select Your Language</h3>
<form method="post" action="<?= e(app_url('langsave.php')) ?>" class="mt-3" style="max-width: 400px;">
    <div class="form-group mb-3">
        <label for="language">Language</label>
        <select class="form-control" id="language" name="language">
            <option value="en" <?= $current === 'en' ? 'selected' : '' ?>>🇺🇸 English</option>
            <option value="ms" <?= $current === 'ms' ? 'selected' : '' ?>>🇲🇾 Malay (Bahasa Melayu)</option>
            <option value="zh" <?= $current === 'zh' ? 'selected' : '' ?>>🇨🇳 Chinese (中文)</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
