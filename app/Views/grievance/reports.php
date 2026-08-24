<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>Reports</h2>
        <small>Generate laporan bulanan grievance dalam format resmi perusahaan.</small>
    </div>
</div>

<div class="report-grid">

    <div class="card report-card">

        <div class="report-icon"><i class="bi bi-file-earmark-excel"></i></div>

        <h4>Rekap Bulanan Grievance</h4>
        <p>
            Laporan sesuai format formulir HRD — rincian case per baris, rekap jumlah per kategori,
            dan blok tanda tangan Compliance / Kabag Produksi / Compliance Manager.
        </p>
        <div class="report-doc">No. Dokumen: FOR-HR-019/REV_02</div>

        <form action="<?= site_url('grievance/reports/monthly') ?>" method="get">

            <div class="report-filter-row">

                <?php if (count($sites) > 1) : ?>
                    <div class="field">
                        <label>Site</label>
                        <select name="site_id">
                            <option value="">Seluruh Site</option>
                            <?php foreach ($sites as $s) : ?>
                                <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php elseif (count($sites) === 1) : ?>
                    <input type="hidden" name="site_id" value="<?= esc($sites[0]['id']) ?>">
                <?php endif; ?>

                <div class="field">
                    <label>Bulan</label>
                    <select name="month">
                        <?php
                        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                        foreach ($months as $k => $v) : ?>
                            <option value="<?= $k ?>" <?= $k == date('n') ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label>Tahun</label>
                    <select name="year">
                        <?php for ($y = (int) date('Y'); $y >= 2020; $y--) : ?>
                            <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-download"></i> Export Excel
                </button>

            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>