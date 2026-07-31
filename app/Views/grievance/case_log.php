<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>
<div class="page-header my-4">

    <div>

        <h2>Case Log</h2>

        <small>
            View and manage all grievance cases.
        </small>

    </div>

    <a href="<?= site_url('grievance/new-case') ?>"
        class="btn btn-primary">

        <i class="bi bi-plus-circle"></i>

        New Case

    </a>

</div>


<div class="card py-2">

    <div class="toolbar">

        <div class="toolbar-left">

            <input
                id="searchCase"
                type="text"
                placeholder="Search case number, PIC, department...">

        </div>

        <div class="toolbar-right">

            <select id="filterSite">

                <option value="">All Sites</option>

                <?php foreach ($sites as $row): ?>

                    <option value="<?= $row['id'] ?>">

                        <?= esc($row['name']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <select id="filterDepartment">

                <option value="">All Departments</option>

                <?php foreach ($departments as $row): ?>

                    <option value="<?= $row['id'] ?>">

                        <?= esc($row['name']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <select id="filterPriority">

                <option value="">All Priorities</option>

                <?php foreach ($priorities as $row): ?>

                    <option value="<?= $row['id'] ?>">

                        <?= esc($row['name']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <select id="filterStatus">

                <option value="">All Statuses</option>

                <?php foreach ($statuses as $row): ?>

                    <option value="<?= $row['id'] ?>">

                        <?= esc($row['name']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <input
                type="date"
                id="dateFrom">

            <input
                type="date"
                id="dateTo">

            <button
                id="btnRefresh"
                class="btn btn-soft">

                <i class="bi bi-arrow-clockwise"></i>

            </button>

            <button
                id="btnExport"
                class="btn btn-success">

                <i class="bi bi-file-earmark-excel"></i>

                Export

            </button>

        </div>

    </div>


    <table
        id="tableCases"
        class="table table-hover"
        width="100%">

        <thead>

            <tr>

                <th width="140">Case No</th>

                <th width="110">Received</th>

                <th>Department</th>

                <th>Case Type</th>

                <th width="100">Priority</th>

                <th width="120">Status</th>

                <th width="160">PIC</th>

                <th width="120">Due Date</th>

                <th width="80">Action</th>

            </tr>

        </thead>

        <tbody></tbody>

    </table>

</div>

<?= $this->section('script') ?>

<script src="<?= base_url('assets/grievance/js/case_log.js') ?>"></script>

<?= $this->endSection() ?>
<?= $this->endSection() ?>