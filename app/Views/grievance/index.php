<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<?= view('grievance/dashboard') ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>