<?php declare(strict_types=1); ?>
<div class="col-md-3 col-lg-2 sidebar">
    <div class="nav flex-column">
        <div class="nav-item<?= ($activeNav ?? '') === 'general' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('general.php')) ?>">
                <i class="fas fa-home"></i> General
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'calendar' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('calendar.php')) ?>">
                <i class="far fa-calendar"></i> Calendar
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'booking' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('booking.php')) ?>">
                <i class="fas fa-book"></i> Booking
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'my_bookings' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('my_bookings.php')) ?>">
                <i class="fas fa-book"></i> My Bookings
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'settings' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('setting.php')) ?>">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
        <div class="nav-item<?= ($activeNav ?? '') === 'rules' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= e(app_url('rules.php')) ?>">
                <i class="fas fa-file-alt"></i> Rules
            </a>
        </div>
    </div>
</div>
