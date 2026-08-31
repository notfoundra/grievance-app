<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>Import Data — WOVO</h2>
        <small>Upload file Excel raw export dari platform WOVO untuk diimport ke sistem.</small>
    </div>
    <a href="<?= site_url('grievance/case-log') ?>" class="btn btn-soft">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="card form-card mb-4">
    <div class="form-group">
        <label>Channels <span style="color:var(--su-danger)">*</span></label>
        <select name="source" required>
            <option value="">Select Channel</option>

            <option value="wovo_import">Wovo</option>
            <option value="suggestion_box">Suggestion Box</option>

        </select>
        <span class="error-text">Site is required.</span>
    </div>
    <label class="upload-box" id="dropArea">
        <i class="bi bi-file-earmark-excel"></i>
        <strong>Drag & Drop file Excel WOVO, atau klik untuk browse</strong>
        <span>Format .xlsx / .xls — kolom mengikuti ConnectReport Raw export WOVO</span>
        <input id="wovoFile" type="file" name="wovo_file" hidden accept=".xlsx,.xls">
    </label>

    <div id="fileInfo" class="mt-3" style="display:none">
        <div class="file-chip">
            <i class="bi bi-file-earmark"></i>
            <span class="name" id="fileName"></span>
            <button type="button" class="remove" id="btnRemoveFile"><i class="bi bi-x-circle"></i></button>
        </div>
    </div>

    <div class="wizard-footer">
        <div class="spacer"></div>
        <button type="button" id="btnImport" class="btn btn-primary" disabled>
            <i class="bi bi-upload"></i> Mulai Import
        </button>
    </div>

</div>

<div id="resultCard" class="card form-card" style="display:none">
    <h5 style="font-size:.85rem;font-weight:800;margin:0 0 1rem">Hasil Import</h5>
    <div class="review-grid mb-3" id="resultSummary"></div>
    <div id="resultErrors"></div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/import.js') ?>"></script>
<?= $this->endSection() ?>