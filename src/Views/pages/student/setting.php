<?php declare(strict_types=1); ?>
<div class="profile-section">
    <h3><i class="fas fa-cog me-2"></i><?= e(__('settings')) ?></h3>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-user text-primary me-2"></i><?= e(__('profile')) ?>
                </h5>
                <p class="card-text"><?= e(__('profile_card_text')) ?></p>
                <a href="<?= e(app_url('profile.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i><?= e(__('view_profile')) ?>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-language text-primary me-2"></i><?= e(__('Lang')) ?>
                </h5>
                <p class="card-text"><?= e(__('language_card_text')) ?></p>
                <a href="<?= e(app_url('language.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i><?= e(__('change_language')) ?>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-question-circle text-primary me-2"></i><?= e(__('Sup')) ?>
                </h5>
                <p class="card-text"><?= e(__('support_card_text')) ?></p>
                <a href="<?= e(app_url('support.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i><?= e(__('get_support')) ?>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-file-alt text-primary me-2"></i><?= e(__('rules')) ?>
                </h5>
                <p class="card-text"><?= e(__('rules_card_text')) ?></p>
                <a href="<?= e(app_url('rules.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i><?= e(__('view_rules')) ?>
                </a>
            </div>
        </div>
    </div>
</div>
