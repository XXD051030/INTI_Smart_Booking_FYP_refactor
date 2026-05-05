<?php declare(strict_types=1); ?>
<div class="header">
    <div class="d-flex align-items-center">
        <img src="<?= e(asset_url('images/logo/inti_logo.png')) ?>" alt="INTI Logo" height="40">
        <h2 class="ms-3 mb-0"><?= e($headerTitle ?? 'Reservation Dashboard') ?></h2>
    </div>
    <div class="d-flex align-items-center">
        <div class="position-relative me-3">
            <i class="fas fa-bell fs-4 notification-icon" id="notification-icon"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count">
                <?= e((string) ($notificationCount ?? 0)) ?>
            </span>
        </div>
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <span><?= e(strtoupper(substr((string) ($currentUser['display_name'] ?? ''), 0, 1))) ?></span>
            </div>
            <span class="ms-2 me-3"><?= e((string) ($currentUser['display_name'] ?? '')) ?></span>
            <a href="<?= e(app_url('logout.php')) ?>" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> <?= e(__('logout')) ?>
            </a>
        </div>
    </div>
</div>
