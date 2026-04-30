<?php declare(strict_types=1); ?>
<div class="profile-section">
    <h3><i class="fas fa-cog me-2"></i>Settings</h3>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-user text-primary me-2"></i>Profile
                </h5>
                <p class="card-text">Manage your personal information and account details.</p>
                <a href="<?= e(app_url('profile.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i>View Profile
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-language text-primary me-2"></i>Language
                </h5>
                <p class="card-text">Choose your preferred language for the interface.</p>
                <a href="<?= e(app_url('language.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i>Change Language
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
                    <i class="fas fa-question-circle text-primary me-2"></i>Support
                </h5>
                <p class="card-text">Get help and contact support for any issues.</p>
                <a href="<?= e(app_url('support.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i>Get Support
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-file-alt text-primary me-2"></i>Rules
                </h5>
                <p class="card-text">Review the rules and regulations for facility booking.</p>
                <a href="<?= e(app_url('rules.php')) ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i>View Rules
                </a>
            </div>
        </div>
    </div>
</div>
