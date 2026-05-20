<?php declare(strict_types=1); ?>
<!-- Statistics Section -->
<div id="stats" class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card users">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1"><?= e(__('admin_stat_total_users')) ?></h5>
                    <h2 class="mb-0"><?= e((string) ($stats['total_users'] ?? 0)) ?></h2>
                </div>
                <i class="fas fa-users fa-2x text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card verified">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1"><?= e(__('admin_stat_verified')) ?></h5>
                    <h2 class="mb-0"><?= e((string) ($stats['verified_users'] ?? 0)) ?></h2>
                </div>
                <i class="fas fa-user-check fa-2x text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card unverified">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1"><?= e(__('admin_stat_unverified')) ?></h5>
                    <h2 class="mb-0"><?= e((string) ($stats['unverified_users'] ?? 0)) ?></h2>
                </div>
                <i class="fas fa-user-times fa-2x text-warning"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card otps">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1"><?= e(__('admin_stat_active_otps')) ?></h5>
                    <h2 class="mb-0"><?= e((string) ($otpStats['active_otps'] ?? 0)) ?></h2>
                </div>
                <i class="fas fa-key fa-2x text-danger"></i>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div id="users" class="table-card">
    <h4 class="table-header">
        <i class="fas fa-users me-2"></i><?= e(__('admin_registered_users')) ?>
    </h4>
    <div class="p-3 border-bottom">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="userSearch" placeholder="<?= e(__('admin_search_users_placeholder')) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="verificationFilter">
                    <option value=""><?= e(__('admin_filter_all_users')) ?></option>
                    <option value="1"><?= e(__('admin_filter_verified_only')) ?></option>
                    <option value="0"><?= e(__('admin_filter_unverified_only')) ?></option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary w-100" onclick="clearFilters()">
                    <i class="fas fa-times me-1"></i><?= e(__('admin_clear_filters')) ?>
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th><?= e(__('admin_table_id')) ?></th>
                    <th><?= e(__('admin_table_username')) ?></th>
                    <th><?= e(__('admin_table_email')) ?></th>
                    <th><?= e(__('admin_table_status')) ?></th>
                    <th><?= e(__('admin_table_created')) ?></th>
                    <th><?= e(__('admin_table_last_updated')) ?></th>
                    <th><?= e(__('admin_table_actions')) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= e((string) $u['id']) ?></td>
                            <td><?= e((string) $u['display_name']) ?></td>
                            <td><?= e((string) $u['email']) ?></td>
                            <td>
                                <span class="badge badge-verified"><?= e(__('admin_badge_verified')) ?></span>
                            </td>
                            <td><?= e(date('Y-m-d H:i', strtotime((string) $u['created_at']))) ?></td>
                            <td><?= e(date('Y-m-d H:i', strtotime((string) ($u['updated_at'] ?? $u['created_at'])))) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary btn-sm" onclick="editUser(<?= e((string) $u['id']) ?>, '<?= e((string) $u['display_name']) ?>', '<?= e((string) $u['email']) ?>')" title="<?= e(__('admin_tooltip_edit_user')) ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="resetPassword(<?= e((string) $u['id']) ?>, '<?= e((string) $u['display_name']) ?>')" title="<?= e(__('admin_tooltip_reset_password')) ?>">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= e((string) $u['id']) ?>, '<?= e((string) $u['display_name']) ?>')" title="<?= e(__('admin_tooltip_delete_user')) ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted"><?= e(__('admin_no_users_found')) ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- OTP Table (deferred - empty in V2) -->
<div id="otps" class="table-card">
    <h4 class="table-header">
        <i class="fas fa-key me-2"></i><?= e(__('admin_otp_history')) ?>
    </h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th><?= e(__('admin_table_id')) ?></th>
                    <th><?= e(__('admin_otp_user')) ?></th>
                    <th><?= e(__('admin_table_email')) ?></th>
                    <th><?= e(__('admin_otp_code')) ?></th>
                    <th><?= e(__('admin_otp_expires')) ?></th>
                    <th><?= e(__('admin_otp_created')) ?></th>
                    <th><?= e(__('admin_table_status')) ?></th>
                    <th><?= e(__('admin_table_actions')) ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" class="text-center text-muted"><?= e(__('admin_otp_deferred')) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= e(__('admin_modal_edit_user')) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label"><?= e(__('admin_modal_username')) ?></label>
                        <input type="text" class="form-control" id="editUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label"><?= e(__('admin_modal_email')) ?></label>
                        <input type="email" class="form-control" id="editEmail" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('admin_modal_cancel')) ?></button>
                <button type="button" class="btn btn-primary" onclick="saveUserChanges()"><?= e(__('admin_modal_save_changes')) ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= e(__('admin_modal_reset_password')) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="resetPasswordForm">
                    <input type="hidden" id="resetUserId">
                    <div class="mb-3">
                        <label for="resetUsername" class="form-label"><?= e(__('admin_modal_username')) ?></label>
                        <input type="text" class="form-control" id="resetUsername" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label"><?= e(__('admin_modal_new_password')) ?></label>
                        <input type="password" class="form-control" id="newPassword" required minlength="6">
                        <div class="form-text"><?= e(__('admin_modal_password_hint')) ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label"><?= e(__('admin_modal_confirm_password')) ?></label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= e(__('admin_modal_cancel')) ?></button>
                <button type="button" class="btn btn-warning" onclick="savePasswordReset()"><?= e(__('admin_modal_reset_btn')) ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    window.ADMIN_LABELS = {
        confirm_delete: <?= json_encode(__('admin_js_confirm_delete'), JSON_UNESCAPED_UNICODE) ?>,
        user_deleted: <?= json_encode(__('admin_js_user_deleted'), JSON_UNESCAPED_UNICODE) ?>,
        user_updated: <?= json_encode(__('admin_js_user_updated'), JSON_UNESCAPED_UNICODE) ?>,
        password_mismatch: <?= json_encode(__('admin_js_password_mismatch'), JSON_UNESCAPED_UNICODE) ?>,
        password_too_short: <?= json_encode(__('admin_js_password_too_short'), JSON_UNESCAPED_UNICODE) ?>,
        confirm_reset: <?= json_encode(__('admin_js_confirm_reset'), JSON_UNESCAPED_UNICODE) ?>,
        password_reset: <?= json_encode(__('admin_js_password_reset'), JSON_UNESCAPED_UNICODE) ?>,
        error_prefix: <?= json_encode(__('admin_js_error_prefix'), JSON_UNESCAPED_UNICODE) ?>,
        error_delete: <?= json_encode(__('admin_js_error_delete'), JSON_UNESCAPED_UNICODE) ?>,
        error_update: <?= json_encode(__('admin_js_error_update'), JSON_UNESCAPED_UNICODE) ?>,
        error_reset: <?= json_encode(__('admin_js_error_reset'), JSON_UNESCAPED_UNICODE) ?>
    };
    const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    function csrfHeaders() { return { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': csrfToken }; }
    function csrfBody(extra) { return extra + '&_token=' + encodeURIComponent(csrfToken); }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    function deleteUser(userId, username) {
        if (confirm(window.ADMIN_LABELS.confirm_delete.replace('{username}', username))) {
            fetch('<?= e(admin_url('actions.php')) ?>', {
                method: 'POST',
                headers: csrfHeaders(),
                body: csrfBody(`action=delete_user&user_id=${encodeURIComponent(userId)}`)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(window.ADMIN_LABELS.user_deleted);
                    location.reload();
                } else {
                    alert(window.ADMIN_LABELS.error_prefix + data.message);
                }
            })
            .catch(error => alert(window.ADMIN_LABELS.error_delete + error));
        }
    }

    function editUser(userId, username, email) {
        document.getElementById('editUserId').value = userId;
        document.getElementById('editUsername').value = username;
        document.getElementById('editEmail').value = email;
        const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
        modal.show();
    }

    function saveUserChanges() {
        const userId = document.getElementById('editUserId').value;
        const username = document.getElementById('editUsername').value;
        const email = document.getElementById('editEmail').value;

        fetch('<?= e(admin_url('actions.php')) ?>', {
            method: 'POST',
            headers: csrfHeaders(),
            body: csrfBody(`action=edit_user&user_id=${encodeURIComponent(userId)}&username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}`)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(window.ADMIN_LABELS.user_updated);
                location.reload();
            } else {
                alert(window.ADMIN_LABELS.error_prefix + data.message);
            }
        })
        .catch(error => alert(window.ADMIN_LABELS.error_update + error));
    }

    function resetPassword(userId, username) {
        document.getElementById('resetUserId').value = userId;
        document.getElementById('resetUsername').value = username;
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
        const modal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
        modal.show();
    }

    function savePasswordReset() {
        const userId = document.getElementById('resetUserId').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword !== confirmPassword) {
            alert(window.ADMIN_LABELS.password_mismatch);
            return;
        }
        if (newPassword.length < 6) {
            alert(window.ADMIN_LABELS.password_too_short);
            return;
        }

        if (confirm(window.ADMIN_LABELS.confirm_reset)) {
            fetch('<?= e(admin_url('actions.php')) ?>', {
                method: 'POST',
                headers: csrfHeaders(),
                body: csrfBody(`action=reset_password&user_id=${encodeURIComponent(userId)}&new_password=${encodeURIComponent(newPassword)}`)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(window.ADMIN_LABELS.password_reset);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal'));
                    modal.hide();
                } else {
                    alert(window.ADMIN_LABELS.error_prefix + data.message);
                }
            })
            .catch(error => alert(window.ADMIN_LABELS.error_reset + error));
        }
    }

    function filterUsers() {
        const searchValue = document.getElementById('userSearch').value.toLowerCase();
        const table = document.getElementById('usersTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const username = row.cells[1]?.textContent.toLowerCase() || '';
            const email = row.cells[2]?.textContent.toLowerCase() || '';
            row.style.display = (!searchValue || username.includes(searchValue) || email.includes(searchValue)) ? '' : 'none';
        }
    }

    function clearFilters() {
        document.getElementById('userSearch').value = '';
        document.getElementById('verificationFilter').value = '';
        filterUsers();
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('userSearch').addEventListener('input', filterUsers);
        document.getElementById('verificationFilter').addEventListener('change', filterUsers);
    });
</script>
