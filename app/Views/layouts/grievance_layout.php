<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title><?= $title ?? 'Grievance System'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="<?= base_url('assets/logo1.png') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/dataTables.dataTables.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/grievance/css/soft-ui.css') ?>">

</head>

<body>

    <div class="app">

        <?= $this->include('layouts/sidebar') ?>

        <main class="main">

            <?= $this->include('layouts/topbar') ?>

            <?= $this->renderSection('content') ?>

        </main>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/js/dataTables.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= base_url('assets/grievance/js/grievance.js') ?>"></script>


    <script>
        const APP = {
            baseUrl: "<?= base_url() ?>/"
        };
    </script>

    <?= $this->renderSection('script') ?>

</body>

</html>