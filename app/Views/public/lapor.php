<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Formulir Penyampaian Saran — PT Kahatex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="robots" content="noindex">
    <link rel="icon" href="<?= base_url('assets/logo1.png') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/grievance/css/soft-ui.css') ?>">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(160deg, #1a1f37 0%, #252f52 55%, var(--su-primary-dark) 100%);
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .lapor-shell {
            width: 100%;
            max-width: 520px;
            margin-top: 1rem
        }

        .lapor-brand {
            text-align: center;
            color: #fff;
            margin-bottom: 1.25rem
        }

        .lapor-brand img {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #fff;
            padding: 6px;
            margin-bottom: .6rem
        }

        .lapor-brand h1 {
            font-size: 1.05rem;
            margin: 0 0 .2rem;
            font-weight: 800
        }

        .lapor-brand p {
            font-size: .75rem;
            margin: 0;
            opacity: .8
        }

        .lapor-card {
            background: #fff;
            border-radius: var(--su-radius);
            padding: 1.5rem 1.25rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, .3)
        }

        .lapor-card .form-group {
            margin-bottom: 1.1rem
        }

        .lapor-card label {
            font-size: .75rem;
            font-weight: 700;
            color: #445069;
            text-transform: uppercase;
            letter-spacing: .3px;
            display: block;
            margin-bottom: .4rem
        }

        .lapor-card select,
        .lapor-card textarea,
        .lapor-card input[type=file] {
            width: 100%;
            border: 1px solid #eef0f7;
            background: #f8f9fe;
            border-radius: .7rem;
            padding: .8rem .9rem;
            font-size: .9rem;
            color: var(--su-dark);
            outline: none;
            font-family: inherit;
        }

        .lapor-card select:focus,
        .lapor-card textarea:focus {
            border-color: var(--su-primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(94, 114, 228, .12)
        }

        .lapor-card textarea {
            resize: vertical;
            min-height: 140px
        }

        .gender-toggle {
            display: flex;
            gap: .6rem
        }

        .gender-toggle label {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border: 1px solid #eef0f7;
            background: #f8f9fe;
            border-radius: .7rem;
            padding: .8rem;
            font-size: .85rem;
            font-weight: 700;
            color: #5b6a85;
            cursor: pointer;
            text-transform: none;
            margin: 0;
        }

        .gender-toggle input {
            margin: 0
        }

        .btn-submit-lapor {
            width: 100%;
            border: 0;
            background: linear-gradient(135deg, var(--su-primary), var(--su-primary-dark));
            color: #fff;
            padding: 1rem;
            border-radius: .8rem;
            font-weight: 800;
            font-size: .9rem;
            cursor: pointer;
        }

        .lapor-footnote {
            text-align: center;
            color: rgba(255, 255, 255, .65);
            font-size: .68rem;
            margin-top: 1.25rem
        }

        .lapor-alert {
            padding: .9rem 1rem;
            border-radius: .7rem;
            font-size: .8rem;
            margin-bottom: 1.1rem;
            background: #feeaef;
            color: var(--su-danger)
        }

        .lapor-alert ul {
            margin: .3rem 0 0;
            padding-left: 1.1rem
        }

        .lapor-success {
            text-align: center;
            padding: 2rem 1rem
        }

        .lapor-success i {
            font-size: 3rem;
            color: var(--su-success);
            margin-bottom: 1rem;
            display: block
        }

        .lapor-success h2 {
            font-size: 1.05rem;
            color: var(--su-dark);
            margin: 0 0 .5rem
        }

        .lapor-success p {
            font-size: .82rem;
            color: var(--su-muted);
            margin: 0 0 1.25rem
        }

        .required::after {
            content: " *";
            color: var(--su-danger)
        }
    </style>
</head>

<body>

    <div class="lapor-shell">

        <div class="lapor-brand">
            <img src="<?= base_url('assets/logo1.png') ?>" alt="PT Kahatex">
            <h1>PT KAHATEX</h1>
            <p>FORMULIR PENYAMPAIAN SARAN</p>
        </div>

        <div class="lapor-card">

            <?php if (session()->getFlashdata('lapor_success')) : ?>

                <div class="lapor-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <h2>Terima kasih!</h2>
                    <p>Saran Anda telah kami terima dan akan segera ditindaklanjuti oleh tim terkait.</p>
                    <a href="<?= site_url('lapor') ?>" class="btn btn-primary" style="text-decoration:none;display:inline-block">
                        Kirim Saran Lain
                    </a>
                </div>

            <?php else : ?>

                <?php if (session()->getFlashdata('lapor_error')) : ?>
                    <div class="lapor-alert"><?= esc(session()->getFlashdata('lapor_error')) ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('lapor_errors')) : ?>
                    <div class="lapor-alert">
                        Mohon periksa kembali isian Anda:
                        <ul>
                            <?php foreach (session()->getFlashdata('lapor_errors') as $err) : ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('lapor/submit') ?>" method="post" enctype="multipart/form-data">

                    <?= csrf_field() ?>

                    <?php $hp = config('Honeypot'); ?>
                    <div id="<?= esc($hp->containerId, 'attr') ?>" style="display:none">
                        <label><?= esc($hp->label) ?></label>
                        <input type="text" name="<?= esc($hp->name, 'attr') ?>" value="">
                    </div>

                    <div class="form-group">
                        <label class="required">Site / Lokasi Anda</label>
                        <select name="site_id" required>
                            <option value="">Pilih Site</option>
                            <?php foreach ($sites as $s) : ?>
                                <option value="<?= $s['id'] ?>" <?= old('site_id') == $s['id'] ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="required">Jenis Kelamin</label>
                        <div class="gender-toggle">
                            <label><input type="radio" name="gender" value="Male" <?= old('gender') === 'Male' ? 'checked' : '' ?> required> Laki-laki</label>
                            <label><input type="radio" name="gender" value="Female" <?= old('gender') === 'Female' ? 'checked' : '' ?>> Perempuan</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="required">Kategori Saran</label>
                        <select name="case_type_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($caseTypes as $ct) : ?>
                                <option value="<?= $ct['id'] ?>" <?= old('case_type_id') == $ct['id'] ? 'selected' : '' ?>><?= esc($ct['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="required">Saran / Keluh Kesah / Pertanyaan</label>
                        <textarea name="message" placeholder="Tuliskan saran, keluhan, atau pertanyaan Anda di sini..." required><?= esc(old('message')) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Lampirkan Foto (opsional)</label>
                        <input type="file" name="attachment[]" accept="image/*,.pdf" capture="environment" multiple>
                    </div>

                    <button type="submit" class="btn-submit-lapor">
                        <i class="bi bi-send"></i> Kirim Saran
                    </button>

                </form>

            <?php endif; ?>

        </div>

        <p class="lapor-footnote">
            Data Anda akan diproses secara rahasia sesuai kebijakan perusahaan.<br>
            FOR-HR-018 · PT Kahatex
        </p>

    </div>

</body>

</html>