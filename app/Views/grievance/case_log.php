<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header my-4">

    <div>
        <h2>Case Log</h2>
        <small>View and manage all grievance cases.</small>
    </div>

    <a href="<?= site_url('case/new') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Case
    </a>

</div>

<div class="card py-2">

    <div class="toolbar">

        <div class="toolbar-left">
            <input id="searchCase" type="text" placeholder="Search case number, PIC, department...">
        </div>

        <div class="toolbar-right">

            <select id="filterDepartment">
                <option value="">All Departments</option>
                <?php foreach ($departments as $row) : ?>
                    <option value="<?= esc($row['name']) ?>"><?= esc($row['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterPriority">
                <option value="">All Priorities</option>
                <?php foreach ($priorities as $row) : ?>
                    <option value="<?= esc($row['name']) ?>"><?= esc($row['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <select id="filterStatus">
                <option value="">All Statuses</option>
                <?php foreach ($statuses as $row) : ?>
                    <option value="<?= esc($row['name']) ?>"><?= esc($row['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <input type="date" id="dateFrom">
            <input type="date" id="dateTo">

            <button id="btnRefresh" class="btn btn-soft btn-icon" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i>
            </button>

            <button id="btnExport" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Export
            </button>

        </div>

    </div>

    <table id="tableCases" class="table table-hover" style="width:100%">

        <thead>
            <tr>
                <th>Case No</th>
                <th>Received</th>
                <th>Department</th>
                <th>Type</th>
                <th>Message</th>
                <th>Priority</th>
                <th>Status</th>
                <th>PIC</th>
                <th>Due Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody></tbody>

    </table>

</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/case_log.js') ?>"></script>
<?= $this->endSection() ?>