<?php declare(strict_types=1); ?>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="<?= e(asset_url('images/logo/inti_logo.png')) ?>" alt="INTI Logo" class="login-logo">
            <h2>Welcome Back</h2>
            <p class="text-muted">Sign in to your account</p>
        </div>

        <form class="login-form" id="loginForm">
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="password-wrapper">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
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
                        Remember me
                    </label>
                </div>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login" id="loginButton">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="loginSpinner"></span>
                <span id="loginButtonText"><i class="fas fa-sign-in-alt"></i> Sign In</span>
            </button>

            <div class="signup-link">
                <p>Don't have an account? <a href="<?= e(app_url('register.php')) ?>">Sign up here</a></p>
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
        loginButtonText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
        errorMessage.classList.add('d-none');

        fetch('<?= e(app_url('login_handler.php')) ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= e(app_url('general.php')) ?>';
            } else {
                errorMessage.textContent = data.message;
                errorMessage.classList.remove('d-none');

                loginButton.disabled = false;
                loginSpinner.classList.add('d-none');
                loginButtonText.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
            }
        })
        .catch(error => {
            errorMessage.textContent = 'An error occurred. Please try again.';
            errorMessage.classList.remove('d-none');

            loginButton.disabled = false;
            loginSpinner.classList.add('d-none');
            loginButtonText.innerHTML = '<i class="fas fa-sign-in-alt"></i> Sign In';
        });
    });

    window.addEventListener('load', function() {
        document.querySelector('.login-card').classList.add('fade-in');
    });
</script>
