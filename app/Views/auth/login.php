<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>Login — Grievance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/logo1.png') ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/grievance/css/soft-ui.css') ?>">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a1f37 0%, #252f52 55%, var(--su-primary-dark) 100%);
            padding: 1.5rem;
        }

        .login-shell {
            width: 100%;
            max-width: 880px;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            border-radius: var(--su-radius);
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .35);
        }

        /* LEFT — brand panel */
        .login-brand {
            background: linear-gradient(160deg, var(--su-primary), var(--su-info) 120%);
            color: #fff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-brand::before,
        .login-brand::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .login-brand::before {
            width: 220px;
            height: 220px;
            top: -70px;
            right: -60px;
        }

        .login-brand::after {
            width: 160px;
            height: 160px;
            bottom: -50px;
            left: -40px;
        }

        .login-brand .logo-badge {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            background: #fff;
            display: grid;
            place-items: center;
            margin-bottom: 1.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
            position: relative;
            z-index: 1;
        }

        .login-brand .logo-badge img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .login-brand h1 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 0 .5rem;
            position: relative;
            z-index: 1;
        }

        .login-brand p {
            font-size: .82rem;
            color: rgba(255, 255, 255, .85);
            line-height: 1.6;
            margin: 0;
            max-width: 320px;
            position: relative;
            z-index: 1;
        }

        .login-brand .role-tags {
            display: flex;
            gap: .5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .login-brand .role-tags span {
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .25);
            padding: .35rem .75rem;
            border-radius: 50rem;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .3px;
        }

        /* RIGHT — form panel */
        .login-form-panel {
            background: #fff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form-panel h2 {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--su-dark);
            margin: 0 0 .3rem;
        }

        .login-form-panel .subtitle {
            font-size: .78rem;
            color: var(--su-muted);
            margin-bottom: 1.75rem;
        }

        .field {
            margin-bottom: 1.1rem;
        }

        .field label {
            display: block;
            font-size: .72rem;
            font-weight: 700;
            color: #445069;
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--su-muted);
            font-size: .9rem;
        }

        .input-wrap input {
            width: 100%;
            border: 1px solid #eef0f7;
            background: #f8f9fe;
            border-radius: .75rem;
            padding: .75rem .9rem .75rem 2.4rem;
            font-size: .82rem;
            outline: none;
            transition: .2s;
        }

        .input-wrap input:focus {
            border-color: var(--su-primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(94, 114, 228, .12);
        }

        .btn-login {
            width: 100%;
            border: 0;
            background: linear-gradient(135deg, var(--su-primary), var(--su-primary-dark));
            color: #fff;
            padding: .85rem;
            border-radius: .75rem;
            font-weight: 700;
            font-size: .82rem;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(94, 114, 228, .35);
            transition: .2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(94, 114, 228, .45);
        }

        .alert {
            padding: .7rem .9rem;
            border-radius: .7rem;
            font-size: .75rem;
            margin-bottom: 1.1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .alert-error {
            background: #feeaef;
            color: var(--su-danger);
        }

        .alert-success {
            background: #e6fbf3;
            color: var(--su-success);
        }

        .footnote {
            text-align: center;
            font-size: .68rem;
            color: var(--su-muted);
            margin-top: 1.5rem;
        }

        @media (max-width: 760px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-brand {
                padding: 2.25rem;
            }

            .login-brand .role-tags {
                margin-top: 1.25rem;
            }
        }
    </style>

</head>

<body>

    <div class="login-shell">

        <!-- LEFT BRAND PANEL -->
        <div class="login-brand">

            <div class="logo-badge">
                <img src="<?= base_url('assets/logo1.png') ?>" alt="PT Kahatex">
            </div>

            <h1>PT KAHATEX</h1>
            <p>Grievance Management System — platform pencatatan, tindak lanjut, dan pelaporan keluhan pekerja secara terpusat.</p>

            <div class="role-tags">
                <span><i class="bi bi-shield-check"></i> Admin</span>
                <span><i class="bi bi-diagram-3"></i> Socks</span>
                <span><i class="bi bi-diagram-3"></i> Garmen</span>
            </div>

        </div>

        <!-- RIGHT FORM PANEL -->
        <div class="login-form-panel">

            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk untuk mengakses dashboard grievance.</p>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <?= esc(session()->getFlashdata('message')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="field">
                    <label>Username / Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-person"></i>
                        <input
                            type="text"
                            name="username"
                            value="<?= esc(old('username')) ?>"
                            placeholder="Masukkan username"
                            autofocus
                            required>
                    </div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock"></i>
                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>

            <div class="footnote">
                &copy; <?= date('Y') ?> PT Kahatex — Internal Use Only
            </div>

        </div>

    </div>

</body>

</html>