<?php declare(strict_types=1); ?>
<div class="admin-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= e(asset_url('images/logo/logowhite.png')) ?>" alt="INTI Logo" class="admin-logo">
                <h3 class="mb-0"><?= e($adminHeaderTitle ?? 'Admin Dashboard') ?></h3>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?= e((string) ($currentAdmin['display_name'] ?? 'Admin')) ?></span>
                <a href="<?= e(admin_url('logout.php')) ?>" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</div>
