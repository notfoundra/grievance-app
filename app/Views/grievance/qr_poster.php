<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>QR Code — Formulir Penyampaian Saran</h2>
        <small>Cetak dan tempel QR Code ini di area kerja agar pekerja dapat mengisi formulir langsung dari HP.</small>
    </div>
</div>

<div class="card" style="padding:2.5rem;text-align:center;max-width:420px;margin:0 auto">
    <img src="<?= site_url('grievance/qr-poster/image') ?>" alt="QR Code Formulir Saran" style="width:260px;height:260px;margin-bottom:1.25rem">
    <p style="font-size:.8rem;color:var(--su-muted);margin-bottom:1.25rem;word-break:break-all">
        <?= esc($formUrl) ?>
    </p>
    <div style="display:flex;gap:.6rem;justify-content:center">
        <a href="<?= site_url('grievance/qr-poster/image') ?>" download="qr-formulir-saran.png" class="btn btn-primary">
            <i class="bi bi-download"></i> Download PNG
        </a>
        <button type="button" class="btn btn-soft" onclick="window.print()">
            <i class="bi bi-printer"></i> Print
        </button>
    </div>
</div>

<?= $this->endSection() ?>