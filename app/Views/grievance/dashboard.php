<div class="filterbar card">

    <div class="field">

        <label>Site</label>

        <select id="dashSite">

            <option value="">All Sites</option>

            <?php foreach ($sites as $site): ?>

                <option value="<?= $site['id'] ?>">

                    <?= esc($site['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="field">

        <label>Year</label>

        <select id="dashYear">

            <option value="">All Years</option>

            <?php

            for ($i = date('Y'); $i >= 2020; $i--):

            ?>

                <option value="<?= $i ?>">

                    <?= $i ?>

                </option>

            <?php endfor; ?>

        </select>

    </div>

    <div class="field">

        <label>Month</label>

        <select id="dashMonth">

            <option value="">All Months</option>

            <?php

            $months = [

                1 => 'January',

                2 => 'February',

                3 => 'March',

                4 => 'April',

                5 => 'May',

                6 => 'June',

                7 => 'July',

                8 => 'August',

                9 => 'September',

                10 => 'October',

                11 => 'November',

                12 => 'December'

            ];

            foreach ($months as $k => $v):

            ?>

                <option value="<?= $k ?>">

                    <?= $v ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>
    <div class="field">

        <label>Status</label>

        <select id="dashStatus">

            <option value="">All Statuses</option>

            <?php foreach ($statuses as $status): ?>

                <option value="<?= $status['id'] ?>">

                    <?= esc($status['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="field">

        <label>Case Type</label>

        <select id="dashType">

            <option value="">All Case Types</option>

            <?php foreach ($caseTypes as $type): ?>

                <option value="<?= $type['id'] ?>">

                    <?= esc($type['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="field">

        <label>Department</label>

        <select id="dashDept">

            <option value="">All Departments</option>

            <?php foreach ($departments as $department): ?>

                <option value="<?= $department['id'] ?>">

                    <?= esc($department['name']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <button class="btn btn-soft" id="resetDash">
        ↺ Reset
    </button>

</div>

<div class="kpis" id="kpiGrid"></div>

<div class="dashboard-grid">

    <div class="chart-card card">

        <div class="card-title">

            <div>

                <h3>Monthly Grievance Trend</h3>

                <small>2026 vs Target</small>

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

        <div class="chart-wrapper donut-wrapper">

            <canvas id="caseTypeChart"></canvas>

        </div>

    </div>

</div>

<div class="bottom-grid">

    <div class="chart-card card">

        <div class="card-title">

            <div>

                <h3>Satisfaction Trend</h3>

                <small>Monthly Average</small>

            </div>

        </div>

        <div class="chart-wrapper">

            <canvas id="satisfactionChart"></canvas>

        </div>

    </div>

    <div class="recent card">

        <div class="card-title">

            <h3>Recent Cases</h3>

            <button class="btn btn-soft btn-sm">

                View All →

            </button>

        </div>

        <div class="table-wrap">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>Case ID</th>

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

            <h3 class="text-danger">

                Overdue Alerts

            </h3>

            <span class="badge badge-danger" id="overdueBadge">

                0

            </span>

        </div>

        <div id="alertList"></div>

    </div>

</div>
<script src="<?= base_url('assets/grievance/js/dashboard.js') ?>"></script>