<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e($pageTitle ?? 'OTP Verification') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <style>
        :root {
            --inti-red: #f61f1f;
            --inti-red-dark: #d41a1a;
        }
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .otp-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            border: 1px solid #eef0f4;
            padding: 2.5rem;
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
        }
        .icon-illustration {
            position: relative;
            width: 110px;
            height: 90px;
            margin: 0 auto 1.5rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-envelope-bg {
            background: var(--inti-red);
            border-radius: 18px;
            width: 90px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            left: 10px;
            top: 15px;
            z-index: 1;
        }
        .icon-envelope {
            color: #fff;
            font-size: 2.8rem;
            z-index: 2;
        }
        .icon-key {
            position: absolute;
            left: -5px;
            top: 35px;
            background: var(--inti-red-dark);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .icon-key i {
            color: #fff;
            font-size: 1.1rem;
        }
        .icon-password {
            position: absolute;
            right: -12px;
            top: 41px;
            background: #fff;
            border-radius: 8px;
            padding: 1px 8px;
            font-size: 0.9rem;
            color: #222;
            font-weight: 600;
            letter-spacing: 0.2em;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            z-index: 4;
        }
        .otp-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .otp-subtitle {
            color: #6c757d;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .otp-email {
            text-align: center;
            font-size: 0.95rem;
            color: #1f2937;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .otp-inputs {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }
        .otp-inputs input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 1.5rem;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            padding: 0;
        }
        .otp-inputs input:focus {
            border-color: var(--inti-red);
            background: white;
            box-shadow: 0 0 0 0.2rem rgba(246, 31, 31, 0.18);
            outline: none;
        }
        .otp-inputs input.filled {
            border-color: var(--inti-red);
            background: white;
        }
        .btn-verify {
            background-color: var(--inti-red);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            margin-top: 1rem;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
            display: block;
            border: none;
        }
        .btn-verify:hover {
            background-color: var(--inti-red-dark);
            color: white;
        }
        .btn-resend {
            background-color: transparent;
            color: var(--inti-red);
            border: none;
            padding: 0.5rem;
            font-weight: 500;
            margin-top: 1.5rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-resend:disabled {
            color: #6c757d;
        }
        .alert {
            border-radius: 8px;
            margin: 1rem auto;
            max-width: 320px;
        }
        .back-button {
            position: fixed;
            top: 1rem;
            left: 1rem;
            background: white;
            border: 1px solid #eef0f4;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2c3e50;
            text-decoration: none;
            z-index: 1000;
        }
        @media (max-width: 480px) {
            .otp-container { padding: 1.5rem; }
            .otp-inputs { gap: 0.5rem; }
            .otp-inputs input { width: 40px; height: 40px; font-size: 1.25rem; }
            .icon-illustration { width: 80px; height: 65px; }
            .icon-envelope-bg { width: 65px; height: 45px; left: 7px; top: 10px; }
            .icon-envelope { font-size: 2rem; }
            .icon-key { width: 22px; height: 22px; left: -7px; top: 25px; }
            .icon-key i { font-size: 0.8rem; }
            .icon-password { right: -22px; top: 25px; font-size: 0.8rem; padding: 1px 10px; }
        }
    </style>
</head>

<body>
    <a href="<?= e(app_url('register.php')) ?>" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container">
        <div class="otp-container">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="<?= e(asset_url('images/logo/inti_logo.png')) ?>" alt="INTI Logo" style="height: 50px; width: auto;">
            </div>

            <div class="icon-illustration">
                <div class="icon-envelope-bg"></div>
                <i class="fa-solid fa-envelope icon-envelope"></i>
                <div class="icon-key"><i class="fa-solid fa-key"></i></div>
                <div class="icon-password">******</div>
            </div>

            <h1 class="otp-title">Verify Your Email Address</h1>
            <p class="otp-subtitle">Click "Send OTP" to receive a 6-digit verification code. If you don't see it, check your spam folder.</p>
            <p class="otp-email"><i class="fa-regular fa-envelope me-1"></i><?= e($email) ?></p>

            <form action="<?= e(app_url('otp-verify.php')) ?>" method="post" id="otpForm">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="verify_otp">
                <input type="hidden" name="otp" id="otpInput">

                <div class="otp-inputs">
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" class="otp-input">
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" class="otp-input">
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" class="otp-input">
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" class="otp-input">
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" class="otp-input">
                    <input type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" class="otp-input">
                </div>

                <?php if ($msg !== ''): ?>
                    <div class="alert alert-<?= $msgType === 'success' ? 'success' : 'danger' ?>" role="alert" id="alertMessage">
                        <?= e($msg) ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-verify">
                    Verify Account
                </button>
            </form>

            <div class="text-center">
                <button type="button"
                        id="send-email"
                        class="btn-resend"
                        onclick="startCountdown('Send OTP', 'Resend OTP', '<?= e(app_url('otp-verify.php')) ?>')">
                    Send OTP
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="<?= e(asset_url('js/countdown.js')) ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const hiddenOtpInput = document.getElementById('otpInput');

            function handleInput(e) {
                const input = e.target;
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value) {
                    input.classList.add('filled');
                } else {
                    input.classList.remove('filled');
                }

                if (input.value && input.nextElementSibling) {
                    input.nextElementSibling.focus();
                }

                hiddenOtpInput.value = Array.from(otpInputs).map(i => i.value).join('');
            }

            function handleBackspace(e) {
                const input = e.target;
                if (e.key === 'Backspace' && !input.value && input.previousElementSibling) {
                    input.previousElementSibling.focus();
                }
            }

            otpInputs.forEach(input => {
                input.addEventListener('input', handleInput);
                input.addEventListener('keydown', handleBackspace);
            });

            document.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').slice(0, 6);
                if (/^\d+$/.test(pastedData)) {
                    otpInputs.forEach((input, index) => {
                        input.value = pastedData[index] || '';
                        if (input.value) input.classList.add('filled');
                    });
                    hiddenOtpInput.value = pastedData;
                }
            });

            const alertMessage = document.getElementById('alertMessage');
            if (alertMessage) {
                setTimeout(function() {
                    alertMessage.style.transition = 'opacity 0.5s ease';
                    alertMessage.style.opacity = '0';
                    setTimeout(function() { alertMessage.remove(); }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>
