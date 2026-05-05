<?php declare(strict_types=1); ?>
<style>
    .profile-card {
        background: #ecf0f5;
        border-radius: 51px;
        padding: 40px;
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: left;
    }
    .profile-card .user-info {
        margin-bottom: 20px;
    }
    .profile-card .user-info i {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 10px;
    }
</style>
<h3><?= e(__('settings')) ?></h3>
<h5><i class="fas fa-user"></i> <?= e(__('profile')) ?></h5>
<div class="profile-card">
    <div class="user-info">
        <p><strong><?= e(__('name')) ?></strong> <?= e((string) $currentUser['display_name']) ?></p>
        <p><strong><?= e(__('sid')) ?></strong> <?= e(student_id_from_email((string) $currentUser['email'])) ?></p>
    </div>
    <div>
         <p class="mb-0"><?= e(__('credit')) ?> <span class="text-success"><?= e(__('credit_good')) ?> <img src="<?= e(asset_url('images/assets/green_tick.png')) ?>" alt="Good" width="20"></span></p>
    </div>
    <div class="form-group">
        <label for="email"><?= e(__('email_address')) ?></label>
        <input type="email" class="form-control" id="email" value="<?= e((string) $currentUser['email']) ?>" readonly>
    </div>
</div>
<hr />
