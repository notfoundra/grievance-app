<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PT KAHATEX — Grievance Mechanism Demo</title>

    <!-- Manggil CSS statis yang tadi lu buat -->
    <link rel="stylesheet" href="<?= base_url('assets/css/grievance.css'); ?>">
</head>

<body>

    <div class="app">
        <!-- Load part Sidebar -->
        <?= $this->include('layout/sidebar'); ?>

        <main class="main">
            <!-- Load part Topbar -->
            <?= $this->include('layout/topbar'); ?>

            <!-- Area ini bakal diisi sama halaman utama (dashboard, form, dll) -->
            <?= $this->renderSection('content'); ?>
        </main>
    </div>

    <!-- Element Toast untuk notifikasi -->
    <div class="toast" id="toast"></div>

    <!-- Manggil Javascript statis yang tadi lu buat -->
    <script src="<?= base_url('assets/js/grievance.js'); ?>"></script>

</body>

</html>