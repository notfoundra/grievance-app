<?php $user = current_user(); ?>

<div class="filterbar card">

    <?php if ($user['role'] === 'admin') : ?>
        <div class="field">
            <label>Site</label>
            <select id="dashSite">
                <option value="">All Sites</option>
                <?php foreach ($sites as $site) : ?>
                    <option value="<?= $site['id'] ?>"><?= esc($site['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else : ?>
        <div class="field">
            <label>Site</label>
            <div class="field-static">
                <i class="bi bi-geo-alt"></i>
                <?= esc($sites[0]['name'] ?? '-') ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="field">
        <label>Date From</label>
        <input type="date" id="dashDateFrom">
    </div>

    <div class="field">
        <label>Date To</label>
        <input type="date" id="dashDateTo">
    </div>

    <div class="field">
        <label>Status</label>
        <select id="dashStatus">
            <option value="">All Statuses</option>
            <?php foreach ($statuses as $status) : ?>
                <option value="<?= $status['id'] ?>"><?= esc($status['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Case Type</label>
        <select id="dashType">
            <option value="">All Case Types</option>
            <?php foreach ($caseTypes as $type) : ?>
                <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Department</label>
        <select id="dashDept">
            <option value="">All Departments</option>
            <?php foreach ($departments as $department) : ?>
                <option value="<?= $department['id'] ?>"><?= esc($department['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="field">
        <label>Gender</label>
        <select id="dashGender">
            <option value="">All Genders</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>

    <button class="btn btn-soft" id="resetDash">
        <i class="bi bi-arrow-counterclockwise"></i> Reset
    </button>
</div>

<div class="kpis" id="kpiGrid"></div>

<div class="dashboard-grid-2">

    <div class="chart-card card">
        <div class="card-title">
            <div>
                <h3>Monthly Grievance Trend</h3>
                <small id="trendYearLabel">Last 6 months</small>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="chart-card card">
        <div class="card-title">
            <div>
                <h3>Cases by Department</h3>
                <small>Top 5</small>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="departmentChart"></canvas>
        </div>
    </div>

    <div class="chart-card card">
        <div class="card-title">
            <div>
                <h3>Case Type</h3>
                <small>Distribution</small>
            </div>
        </div>
        <div class="donut-flex">
            <div class="donut-canvas-wrap">
                <canvas id="caseTypeChart"></canvas>
            </div>
            <div class="donut-legend" id="caseTypeLegend"></div>
        </div>
    </div>

    <div class="chart-card card">
        <div class="card-title">
            <div>
                <h3>Satisfaction</h3>
                <small>Distribution</small>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="satisfactionChart"></canvas>
        </div>
    </div>

    <div class="recent card">
        <div class="card-title">
            <h3>Recent Cases</h3>
            <a href="<?= site_url('grievance/case-log') ?>" class="btn btn-soft btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Case No</th>
                        <th>Department</th>
                        <th>Case Type</th>
                        <th>Status</th>
                        <th>PIC</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody id="recentBody"></tbody>
            </table>
        </div>
    </div>

    <div class="alerts card">
        <div class="card-title">
            <h3 class="text-danger" style="color:var(--su-danger)">Overdue Alerts</h3>
            <span class="status status-overdue" id="overdueBadge">0</span>
        </div>
        <div id="alertList"></div>
    </div>

</div>