<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>Master Data</h2>
        <small>Manage selectable values used across the grievance system.</small>
    </div>
</div>

<div class="tabnav" id="mdTabs">
    <div class="tabnav-item active" data-type="case-type">
        <i class="bi bi-tags"></i> Case Type
    </div>
    <div class="tabnav-item" data-type="channel">
        <i class="bi bi-broadcast"></i> Channel
    </div>
    <div class="tabnav-item" data-type="department">
        <i class="bi bi-diagram-3"></i> Department
    </div>
</div>

<div class="card master-table-card">

    <div class="master-table-head">
        <div>
            <h3 id="mdTitle">Case Type</h3>
            <small id="mdCount">0 entries</small>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddMaster">
            <i class="bi bi-plus-circle"></i> Add New
        </button>
    </div>

    <div class="table-wrap">
        <table class="data-table" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th style="width:90px">Status</th>
                    <th style="width:70px">Action</th>
                </tr>
            </thead>
            <tbody id="mdTableBody"></tbody>
        </table>
    </div>

</div>

<!-- ===================== MODAL: ADD / EDIT ===================== -->
<div class="modal-overlay" id="modalMaster">
    <div class="modal-box modal-sm">

        <div class="modal-header">
            <h4 id="modalMasterTitle"><i class="bi bi-plus-circle"></i> Add Case Type</h4>
            <button type="button" class="modal-close" data-close="modalMaster"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="formMaster">

            <div class="modal-body">

                <input type="hidden" id="mdId" value="">

                <div class="form-group mb-3">
                    <label>Name <span style="color:var(--su-danger)">*</span></label>
                    <input type="text" id="mdName" name="name" required maxlength="150">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea id="mdDescription" name="description" rows="3"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-soft" data-close="modalMaster">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save</button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/master_data.js') ?>"></script>
<?= $this->endSection() ?>