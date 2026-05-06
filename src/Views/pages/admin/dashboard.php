<?php declare(strict_types=1); ?>
<!-- Statistics Section -->
<div id="stats" class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card users">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-muted mb-1">Total Users</h5>
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
                    <h5 class="text-muted mb-1">Verified Users</h5>
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
                    <h5 class="text-muted mb-1">Unverified Users</h5>
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
                    <h5 class="text-muted mb-1">Active OTPs</h5>
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
        <i class="fas fa-users me-2"></i>Registered Users
    </h4>
    <div class="p-3 border-bottom">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="userSearch" placeholder="Search users by username or email...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="verificationFilter">
                    <option value="">All Users</option>
                    <option value="1">Verified Only</option>
                    <option value="0">Unverified Only</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-secondary w-100" onclick="clearFilters()">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover" id="usersTable">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
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
                                <span class="badge badge-verified">Verified</span>
                            </td>
                            <td><?= e(date('Y-m-d H:i', strtotime((string) $u['created_at']))) ?></td>
                            <td><?= e(date('Y-m-d H:i', strtotime((string) ($u['updated_at'] ?? $u['created_at'])))) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-primary btn-sm" onclick="editUser(<?= e((string) $u['id']) ?>, '<?= e((string) $u['display_name']) ?>', '<?= e((string) $u['email']) ?>')" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="resetPassword(<?= e((string) $u['id']) ?>, '<?= e((string) $u['display_name']) ?>')" title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= e((string) $u['id']) ?>, '<?= e((string) $u['display_name']) ?>')" title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- OTP Table (deferred - empty in V2) -->
<div id="otps" class="table-card">
    <h4 class="table-header">
        <i class="fas fa-key me-2"></i>OTP Verification History
    </h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>OTP Code</th>
                    <th>Expires At</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" class="text-center text-muted">OTP module deferred to Round 2</td>
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
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveUserChanges()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset User Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="resetPasswordForm">
                    <input type="hidden" id="resetUserId">
                    <div class="mb-3">
                        <label for="resetUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="resetUsername" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" required minlength="6">
                        <div class="form-text">Password must be at least 6 characters long</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="savePasswordReset()">Reset Password</button>
            </div>
        </div>
    </div>
</div>

<script>
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
        if (confirm(`Are you sure you want to delete user "${username}"? This action cannot be undone.`)) {
            fetch('<?= e(admin_url('actions.php')) ?>', {
                method: 'POST',
                headers: csrfHeaders(),
                body: csrfBody(`action=delete_user&user_id=${encodeURIComponent(userId)}`)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('User deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Error deleting user: ' + error));
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
                alert('User updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => alert('Error updating user: ' + error));
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
            alert('Passwords do not match!');
            return;
        }
        if (newPassword.length < 6) {
            alert('Password must be at least 6 characters long!');
            return;
        }

        if (confirm('Are you sure you want to reset this user\'s password?')) {
            fetch('<?= e(admin_url('actions.php')) ?>', {
                method: 'POST',
                headers: csrfHeaders(),
                body: csrfBody(`action=reset_password&user_id=${encodeURIComponent(userId)}&new_password=${encodeURIComponent(newPassword)}`)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password reset successfully!');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal'));
                    modal.hide();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Error resetting password: ' + error));
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
