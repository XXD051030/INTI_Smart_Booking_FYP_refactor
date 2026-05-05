<?php declare(strict_types=1); ?>
<?php $loginFlash = pull_flash('login'); ?>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="<?= e(asset_url('images/logo/inti_logo.png')) ?>" alt="INTI Logo" class="login-logo">
            <h2><?= e(__('welcome_back')) ?></h2>
            <p class="text-muted"><?= e(__('signinacc')) ?></p>
        </div>

        <?php if ($loginFlash !== null): ?>
            <div class="alert alert-<?= ($loginFlash['type'] ?? 'success') === 'success' ? 'success' : 'danger' ?> mb-3" role="alert">
                <?= e($loginFlash['message']) ?>
            </div>
        <?php endif; ?>

        <form class="login-form" id="loginForm">
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> <?= e(__('email_address')) ?>
                </label>
                <input type="email" class="form-control" id="email" name="email" placeholder="<?= e(__('enter_your_email')) ?>" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> <?= e(__('pw')) ?>
                </label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password" name="password" placeholder="<?= e(__('enter_your_password')) ?>" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <div id="errorMessage" class="alert alert-danger d-none mb-3"></div>

            <div class="form-options">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        <?= e(__('remember')) ?>
                    </label>
                </div>
                <a href="#" class="forgot-password"><?= e(__('forget')) ?></a>
            </div>

            <button type="submit" class="btn-login" id="loginButton">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="loginSpinner"></span>
                <span id="loginButtonText"><i class="fas fa-sign-in-alt"></i> <?= e(__('signin')) ?></span>
            </button>

            <div class="signup-link">
                <p><?= e(__('donthaveacc')) ?> <a href="<?= e(app_url('register.php')) ?>"><?= e(__('signup')) ?></a></p>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const errorMessage = document.getElementById('errorMessage');
        const loginButton = document.getElementById('loginButton');
        const loginSpinner = document.getElementById('loginSpinner');
        const loginButtonText = document.getElementById('loginButtonText');

        loginButton.disabled = true;
        loginSpinner.classList.remove('d-none');
        loginButtonText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= e(__('signing_in')) ?>';
        errorMessage.classList.add('d-none');

        fetch('<?= e(app_url('login_handler.php')) ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= e(app_url('general.php')) ?>';
            } else if (data.redirect_to) {
                window.location.href = data.redirect_to;
            } else {
                errorMessage.textContent = data.message;
                errorMessage.classList.remove('d-none');

                loginButton.disabled = false;
                loginSpinner.classList.add('d-none');
                loginButtonText.innerHTML = '<i class="fas fa-sign-in-alt"></i> <?= e(__('signin')) ?>';
            }
        })
        .catch(error => {
            errorMessage.textContent = '<?= e(__('an_error_occurred')) ?>';
            errorMessage.classList.remove('d-none');

            loginButton.disabled = false;
            loginSpinner.classList.add('d-none');
            loginButtonText.innerHTML = '<i class="fas fa-sign-in-alt"></i> <?= e(__('signin')) ?>';
        });
    });

    window.addEventListener('load', function() {
        document.querySelector('.login-card').classList.add('fade-in');
    });
</script>
