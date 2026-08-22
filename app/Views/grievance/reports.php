<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>Reports</h2>
        <small>Generate dan export laporan grievance dalam format Excel.</small>
    </div>
</div>

<div class="report-grid">

    <div class="section-card">

        <div class="section-title">
            <i class="bi bi-file-earmark-excel me-2"></i>
            Formulir Tanggapan Saran-Saran Anda
        </div>

        <div class="section-body">

            <p class="text-muted mb-3">
                FOR-HR-019 &middot; Rekap kasus grievance per bulan, lengkap dengan rekap kategori saran.
            </p>

            <form action="<?= site_url('report/export/suggestion-form') ?>" method="get" target="_blank">

                <div class="form-grid form-grid-3 mb-3">

                    <div class="form-group">
                        <label>Tahun</label>
                        <select name="year">
                            <?php for ($y = (int) date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= $y === (int) date('Y') ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bulan</label>
                        <select name="month">
                            <?php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ];
                            foreach ($months as $k => $v): ?>
                                <option value="<?= $k ?>" <?= $k === (int) date('n') ? 'selected' : '' ?>>
                                    <?= $v ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group" style="align-self:end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-earmark-excel"></i>
                            Export Excel
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
