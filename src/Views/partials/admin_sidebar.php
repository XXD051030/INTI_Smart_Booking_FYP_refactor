<?php declare(strict_types=1); ?>
<div class="admin-nav">
    <div class="container">
        <nav class="nav">
            <a class="nav-link<?= ($activeNav ?? '') === 'dashboard' ? ' active' : '' ?>" href="<?= e(admin_url('dashboard.php')) ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            <a class="nav-link<?= ($activeNav ?? '') === 'bookings' ? ' active' : '' ?>" href="<?= e(admin_url('bookings.php')) ?>"><i class="fas fa-calendar-alt me-2"></i>Bookings</a>
        </nav>
    </div>
</div>
