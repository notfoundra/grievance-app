<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>Quisioner</h2>
        <small>Rekap hasil pretest & posttest per sesi training.</small>
    </div>
</div>

<div class="kpis-quiz">
    <div class="kpi card" style="--tone:linear-gradient(135deg,#5e72e4,#324cdd)">
        <div>
            <div class="kpi-label">Total Quisioner</div>
            <div class="kpi-value"><?= (int) $totalBatches ?></div>
        </div>
        <div class="kpi-icon"><i class="bi bi-clipboard-data"></i></div>
    </div>
    <div class="kpi card" style="--tone:linear-gradient(135deg,#11cdef,#1171ef)">
        <div>
            <div class="kpi-label">Total Peserta</div>
            <div class="kpi-value"><?= (int) $totalParticipants ?></div>
        </div>
        <div class="kpi-icon"><i class="bi bi-people"></i></div>
    </div>
    <div class="kpi card" style="--tone:linear-gradient(135deg,#2dce89,#2dcecc)">
        <div>
            <div class="kpi-label">Tingkat Kelulusan</div>
            <div class="kpi-value"><?= esc($passRate) ?>%</div>
        </div>
        <div class="kpi-icon"><i class="bi bi-check-circle"></i></div>
    </div>
    <div class="kpi card" style="--tone:linear-gradient(135deg,#fb6340,#fbb140)">
        <div>
            <div class="kpi-label">Passing Score</div>
            <div class="kpi-value">&ge; <?= (int) $passingScore ?></div>
        </div>
        <div class="kpi-icon"><i class="bi bi-flag"></i></div>
    </div>
</div>

<div class="filterbar card mb-4">
    <div class="field" style="min-width:280px">
        <label>Pilih Quisioner</label>
        <select id="quizSelect">
            <?php if (empty($list)) : ?>
                <option value="">Belum ada data quisioner</option>
            <?php else : ?>
                <?php foreach ($list as $m) : ?>
                    <option value="<?= $m['id'] ?>"><?= esc($m['title']) ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
</div>

<?php if (empty($list)) : ?>

    <div class="card" style="padding:3rem;text-align:center;color:var(--su-muted)">
        Belum ada data master quisioner. Tambahkan dulu sesi quisioner untuk melihat rekap hasil.
    </div>

<?php else : ?>

    <div class="quiz-charts-grid">

        <div class="chart-card card">
            <div class="card-title">
                <div>
                    <h3>Tingkat Kelulusan</h3>
                    <small id="quizBatchLabel">-</small>
                </div>
            </div>
            <div class="chart-wrapper" style="height:230px">
                <canvas id="quizPassChart"></canvas>
            </div>
        </div>

        <div class="chart-card card">
            <div class="card-title">
                <div>
                    <h3>Nilai Pretest vs Posttest per Peserta</h3>
                    <small id="quizAvgLabel">-</small>
                </div>
            </div>
            <div class="chart-wrapper" style="height:230px">
                <canvas id="quizScoreChart"></canvas>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="master-table-head">
            <div>
                <h3>Detail Peserta</h3>
                <small id="quizTableCount">0 peserta</small>
            </div>
        </div>
        <div class="table-wrap">
            <table class="data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>Pretest</th>
                        <th>Posttest</th>
                        <th>Selisih</th>
                        <th>Catatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="quizTableBody"></tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/quisioner.js') ?>"></script>
<?= $this->endSection() ?>