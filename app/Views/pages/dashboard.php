<section class="page active" id="page-dashboard">
    <!-- Filter Bar -->
    <div class="filterbar card">

        <div class="field">
            <label>Year</label>
            <select id="dashYear">
                <option>2026</option>
                <option>2025</option>
                <option>2024</option>
                <option>2023</option>
                <option>2022</option>
                <option>2021</option>
                <option>2020</option>
                <option>2019</option>
            </select>
        </div>
        <div class="field">
            <label>Month</label>
            <select id="dashMonth">
                <option value="All">All Months</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="field">
            <label>Status</label>
            <select id="dashStatus">
                <option value="All">All Statuses</option>
                <option>Open</option>
                <option>In Progress</option>
                <option>Closed</option>
                <option>Overdue</option>
            </select>
        </div>
        <div class="field">
            <label>Case Type</label>
            <select id="dashType">
                <option value="All">All Case Types</option>
            </select>
        </div>
        <div class="field">
            <label>Department</label>
            <select id="dashDept">
                <option value="All">All Departments</option>
            </select>
        </div>
        <button class="btn btn-soft" id="resetDash">↺ Reset</button>
    </div>

    <!-- KPI Cards Grid -->
    <div class="kpis" id="kpiGrid"></div>

    <!-- Charts Row -->
    <div class="dashboard-grid">
        <div class="chart-card card">
            <div class="card-title">
                <h3>Monthly Grievance Trend</h3>
                <small>2026 vs target</small>
            </div>
            <div class="line-chart" id="lineChart"></div>
        </div>
        <div class="chart-card card">
            <div class="card-title">
                <h3>Cases by Department</h3>
                <small>Top 5</small>
            </div>
            <div class="hbar-list" id="deptChart"></div>
        </div>
        <div class="chart-card card">
            <div class="card-title">
                <h3>Case Type</h3>
                <small>Distribution</small>
            </div>
            <div class="donut-wrap">
                <div class="donut" id="typeDonut">
                    <div class="donut-center">
                        <strong id="donutTotal">0</strong>
                        <span>Total</span>
                    </div>
                </div>
                <div class="legend" id="typeLegend"></div>
            </div>
        </div>
    </div>

    <!-- Bottom Row (Satisfaction, Recent Cases, Overdue Alerts) -->
    <div class="bottom-grid">
        <div class="chart-card card">
            <div class="card-title">
                <h3>Satisfaction Trend</h3>
                <small>Monthly average</small>
            </div>
            <div class="satisfaction-chart" id="satChart"></div>
        </div>
        <div class="recent card">
            <div class="card-title">
                <h3>Recent Cases</h3>
                <button class="btn btn-sm btn-soft" onclick="showPage('case-log')">View all →</button>
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
                <h3 style="color:var(--red)">Overdue Alerts</h3>
                <span class="status overdue" id="overdueBadge">0</span>
            </div>
            <div id="alertList"></div>
        </div>
    </div>
</section>