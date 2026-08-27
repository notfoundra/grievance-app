<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>Quisioner</h2>
        <small>Rekap hasil pretest & posttest per sesi training.</small>
    </div>

    <div class="detail-action">
        <a href="<?= site_url('grievance/quisioner/downloadTemplate') ?>" class="btn btn-success">

            <i class="bi bi-file-earmark-excel"></i>Download Template</a>
        <button type="button" class="btn btn-primary" id="btnOpenFollowUp">
            <i class="bi bi-chat-left-text"></i> Follow Up
        </button>
        <button type="button" class="btn btn-primary" id="btnOpenImportQuiz">
            <i class="bi bi-upload"></i> Import Quisioner
        </button>
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
                    <option value="<?= $m['id'] ?>" <?= (string) $selectedId === (string) $m['id'] ? 'selected' : '' ?>><?= esc($m['title']) ?></option>
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
                    <h3>Distribusi Nilai Pretest</h3>
                    <small>Jumlah peserta per rentang nilai</small>
                </div>
            </div>
            <div class="chart-wrapper" style="height:230px">
                <canvas id="quizPretestChart"></canvas>
            </div>
        </div>

        <div class="chart-card card">
            <div class="card-title">
                <div>
                    <h3>Distribusi Nilai Posttest</h3>
                    <small id="quizAvgLabel">-</small>
                </div>
            </div>
            <div class="chart-wrapper" style="height:230px">
                <canvas id="quizPosttestChart"></canvas>
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
<!-- ===================== MODAL: IMPORT QUISIONER ===================== -->
<div class="modal-overlay" id="modalImportQuiz">
    <div class="modal-box modal-sm">

        <div class="modal-header">
            <h4><i class="bi bi-upload"></i> Import Quisioner</h4>
            <button type="button" class="modal-close" data-close="modalImportQuiz"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="formImportQuiz" enctype="multipart/form-data">

            <div class="modal-body">

                <div class="form-group mb-3">
                    <label>Judul Quisioner <span style="color:var(--su-danger)">*</span></label>
                    <input type="text" name="title" maxlength="30" required placeholder="Contoh: Training K3 Batch 1">
                    <span class="hint">Maksimal 30 karakter.</span>
                </div>

                <div class="form-group mb-3">
                    <label>Deskripsi</label>
                    <input type="text" name="description" maxlength="30" placeholder="Contoh: Juli 2026">
                    <span class="hint">Maksimal 30 karakter, harus unik (belum pernah dipakai sebelumnya).</span>
                </div>

                <label class="upload-box" id="quizDropArea">
                    <i class="bi bi-file-earmark-excel"></i>
                    <strong>Pilih atau drag file Excel di sini</strong>
                    <span>Kolom yang dibaca: Nama (B), Nilai Pretest (G), Nilai Posttest (J), Keterangan (K)</span>
                    <input id="quizFileInput" type="file" name="quiz_file" hidden accept=".xlsx,.xls">
                </label>

                <div id="quizFileInfo" class="mt-3" style="display:none">
                    <div class="file-chip">
                        <i class="bi bi-file-earmark"></i>
                        <span class="name" id="quizFileName"></span>
                        <button type="button" class="remove" id="btnRemoveQuizFile"><i class="bi bi-x-circle"></i></button>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-soft" data-close="modalImportQuiz">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitImportQuiz" disabled>
                    <i class="bi bi-upload"></i> Import
                </button>
            </div>

        </form>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script src="<?= base_url('assets/grievance/js/quisioner.js') ?>"></script>

<?= $this->endSection() ?>