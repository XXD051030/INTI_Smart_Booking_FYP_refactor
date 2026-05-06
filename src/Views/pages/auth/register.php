<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="<?= e(current_locale()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e(__('register_title')) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: rgb(246, 31, 31);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }

        .page-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .left-section {
            flex: 1;
            background: linear-gradient(135deg, rgb(246, 31, 31) 0%, rgb(212, 26, 26) 100%);
            padding: 40px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/></svg>') center/cover;
            opacity: 0.1;
        }

        .welcome-text {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
        }

        .welcome-subtext {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .features-list {
            list-style: none;
            margin-top: 30px;
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .features-list i {
            margin-right: 10px;
            color: #4CAF50;
        }

        .right-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            text-align: left;
            margin-bottom: 2rem;
            color: rgb(246, 31, 31);
            font-size: 28px;
            font-weight: 600;
        }

        .input-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input:focus {
            border-color: rgb(246, 31, 31);
            background: white;
            box-shadow: 0 0 0 4px rgba(246, 31, 31, 0.1);
        }

        .input-container label {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            pointer-events: none;
            transition: 0.3s;
            background: transparent;
            padding: 0 5px;
        }

        .input:focus + label,
        .input:not(:placeholder-shown) + label {
            top: 0;
            font-size: 12px;
            color: rgb(246, 31, 31);
            background: white;
        }

        .content, .content-2, .content-3 {
            margin-top: -1rem;
            margin-bottom: 1rem;
            font-size: 14px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }

        .server-feedback {
            margin-top: 0.25rem;
            margin-bottom: 1rem;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 14px;
            display: none;
            align-items: center;
            gap: 10px;
        }

        .server-feedback.is-error {
            background: #fdecec;
            color: #a3140d;
            border: 1px solid #f5c2bd;
        }

        .server-feedback.is-success {
            background: #e7f7ec;
            color: #19663a;
            border: 1px solid #b8e3c5;
        }

        .server-feedback i {
            font-size: 1rem;
        }

        .requirement-list, .requirement-list-2, .requirement-list-3 {
            list-style: none;
            padding: 0;
        }

        .requirement-list li, .requirement-list-2 li, .requirement-list-3 li {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            color: #666;
        }

        .requirement-list i, .requirement-list-2 i, .requirement-list-3 i {
            margin-right: 8px;
        }

        .terms-container {
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .terms-container input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: rgb(246, 31, 31);
        }

        .terms-container label {
            color: #666;
            font-size: 14px;
            cursor: pointer;
        }

        .terms-container a {
            color: rgb(246, 31, 31);
            text-decoration: none;
        }

        .terms-container a:hover {
            text-decoration: underline;
        }

        #submit_btn {
            width: 100%;
            padding: 15px;
            background: rgb(246, 31, 31);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #submit_btn:hover {
            background: rgb(212, 26, 26);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(246, 31, 31, 0.2);
        }

        #submit_btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: rgb(246, 31, 31);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .page-container {
                flex-direction: column;
            }

            .left-section {
                padding: 30px;
            }

            .welcome-text {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="left-section">
          <div style="text-align: center; margin-bottom: 30px;">
            <img src="<?= e(asset_url('images/logo/logowhite.png')) ?>" alt="INTI Logo" style="height: 60px; width: auto;">
          </div>
          <h1 class="welcome-text"><?= e(__('welcome_to_inti')) ?></h1>
          <p class="welcome-subtext">
            <?= e(__('welcome_to_inti_sub')) ?>
          </p>
          <ul class="features-list">
            <li><i class="fas fa-check-circle"></i> <?= e(__('feature_easy_booking')) ?></li>
            <li><i class="fas fa-check-circle"></i> <?= e(__('feature_realtime')) ?></li>
            <li><i class="fas fa-check-circle"></i> <?= e(__('feature_community')) ?></li>
          </ul>

        </div>
        <div class="right-section">
            <h2 class="form-title"><?= e(__('create_account')) ?></h2>
                <div class="input-container">
                    <input type="text" placeholder=" " name="username" id="username" class="input" required>
                    <label for="username"><?= e(__('username')) ?></label>
                </div>

                <div class="input-container">
                    <input type="email" placeholder=" " name="email" id="email" class="input" required>
                    <label for="email"><?= e(__('email_inti')) ?></label>
                </div>

                <div class="content-3" id="email-requirements" style="display: none;">
                    <ul class="requirement-list-3">
                        <li>
                            <i class="fa-solid fa-circle"></i>
                            <span id="email-error"><?= e(__('email_must_be_inti')) ?></span>
                        </li>
                    </ul>
                </div>

                <div class="input-container">
                    <input type="password" placeholder=" " name="password" id="password" class="input" required>
                    <label for="password"><?= e(__('pw')) ?></label>
                </div>

                <div class="content" id="password-requirements" style="display: none;">
                    <ul class="requirement-list">
                        <li>
                            <i class="fa-solid fa-circle"></i>
                            <span><?= e(__('pw_rule_length')) ?></span>
                        </li>
                        <li>
                            <i class="fa-solid fa-circle"></i>
                            <span><?= e(__('pw_rule_number')) ?></span>
                        </li>
                    </ul>
                </div>

                <div class="input-container">
                    <input type="password" placeholder=" " name="password_confirmation" id="password_confirmation" class="input" required>
                    <label for="password_confirmation"><?= e(__('confirm_password')) ?></label>
                </div>

                <div class="content-2" id="password-requirements-2" style="display: none;">
                    <ul class="requirement-list-2">
                        <li>
                            <i class="fa-solid fa-circle"></i>
                            <span id="error"><?= e(__('pw_no_match')) ?></span>
                        </li>
                    </ul>
                </div>

                <div class="terms-container">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms"><?= __('agree_terms_html') ?></label>
                </div>

                <div id="server-feedback" class="server-feedback">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span id="server-feedback-text"></span>
                </div>

                <button type="submit" id="submit_btn" disabled><?= e(__('create_account_btn')) ?></button>

                <div class="login-link">
                    <?= e(__('already_have_account')) ?> <a href="<?= e(app_url('login.php')) ?>"><?= e(__('sign_in_short')) ?></a>
                </div>
        </div>
    </div>

    <script src="<?= e(asset_url('js/validations.js')) ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var $feedback = $('#server-feedback');
            var $feedbackText = $('#server-feedback-text');
            var $feedbackIcon = $feedback.find('i');

            function showFeedback(type, message) {
                $feedback
                    .removeClass('is-error is-success')
                    .addClass(type === 'success' ? 'is-success' : 'is-error');
                $feedbackIcon
                    .removeClass('fa-circle-exclamation fa-circle-check')
                    .addClass(type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation');
                $feedbackText.text(message);
                $feedback.css('display', 'flex');
            }

            $('#submit_btn').click(function(e) {
                e.preventDefault();
                $feedback.hide();

                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var formData = {
                    _token: csrfToken,
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                };

                $.ajax({
                    type: 'POST',
                    url: '<?= e(app_url('process_register.php')) ?>',
                    data: formData,
                    dataType: 'json',
                    headers: { 'X-CSRF-Token': csrfToken },
                    success: function(response) {
                        if (response.success) {
                            showFeedback('success', response.message);
                            var nextUrl = response.redirect_to || '<?= e(app_url('login.php')) ?>';
                            setTimeout(function() {
                                window.location.href = nextUrl;
                            }, 1200);
                        } else {
                            showFeedback('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        var msg = '<?= e(__('something_went_wrong')) ?>';
                        try {
                            var parsed = JSON.parse(xhr.responseText);
                            if (parsed && parsed.message) msg = parsed.message;
                        } catch (e) {}
                        showFeedback('error', msg);
                    }
                });
            });
        });
    </script>

</body>
</html>
