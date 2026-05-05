<?php declare(strict_types=1); ?>
<div class="col-md-3 col-lg-2 sidebar">
    <div class="nav flex-column">
        <div class="nav-item<?= ($activeNav ?? '') === 'general' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('general.php')) ?>">
                <i class="fas fa-home"></i> <?= e(__('general')) ?>
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'calendar' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('calendar.php')) ?>">
                <i class="far fa-calendar"></i> <?= e(__('calendar')) ?>
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'booking' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('booking.php')) ?>">
                <i class="fas fa-book"></i> <?= e(__('booking')) ?>
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'my_bookings' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('my_bookings.php')) ?>">
                <i class="fas fa-book"></i> <?= e(__('mybk')) ?>
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'settings' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('setting.php')) ?>">
                <i class="fas fa-cog"></i> <?= e(__('settings')) ?>
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'rules' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('rules.php')) ?>">
                <i class="fas fa-file-alt"></i> <?= e(__('rules')) ?>
            </a>
        </div>
    </div>
</div>
