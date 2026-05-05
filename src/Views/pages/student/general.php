<?php declare(strict_types=1); ?>
<!-- Profile Section -->
<div class="profile-section d-flex">
    <div class="profile-pic">
        <i class="fas fa-user"></i>
    </div>
    <div>
        <h3><?= e((string) $currentUser['display_name']) ?></h3>
        <p class="mb-1"><?= e(__('email')) ?> <?= e((string) $currentUser['email']) ?></p>
        <p class="mb-1"><?= e(__('status')) ?> <?= e(__('no_appointment')) ?></p>
        <p class="mb-0"><?= e(__('credit')) ?> <span class="text-success"><?= e(__('credit_good')) ?> <img src="<?= e(asset_url('images/assets/green_tick.png')) ?>" alt="Good" width="20"></span></p>
    </div>
</div>


<!-- Places Section -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="place-card card">
            <img src="<?= e(asset_url('images/place/discussion_room.jpg')) ?>" alt="<?= e(__('discussionroom')) ?>">
            <div class="content">
                <h3><?= e(__('discussionroom')) ?></h3>
                <a href="<?= e(app_url('booking.php')) ?>" class="btn-book">
                    <i class="fas fa-plus me-1"></i> <?= e(__('book')) ?>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="place-card card">
            <img src="<?= e(asset_url('images/place/basketball_court.jpg')) ?>" alt="<?= e(__('sport')) ?>">
            <div class="content">
                <h3><?= e(__('sport')) ?></h3>
                <a href="<?= e(app_url('booking.php')) ?>" class="btn-book">
                    <i class="fas fa-plus me-1"></i> <?= e(__('book')) ?>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="place-card card">
            <img src="<?= e(asset_url('images/place/stem_lab.jpg')) ?>" alt="<?= e(__('stem')) ?>">
            <div class="content">
                <h3><?= e(__('stem')) ?></h3>
                <a href="<?= e(app_url('booking.php')) ?>" class="btn-book">
                    <i class="fas fa-plus me-1"></i> <?= e(__('book')) ?>
                </a>
            </div>
        </div>
    </div>
</div>
