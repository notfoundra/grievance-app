const Dashboard = {

    chart: {
        trend: null,
        department: null,
        caseType: null,
        satisfaction: null
    },

    init() {

        this.bindEvent();
        this.load();

    },

    getFilter() {

        return {

            site_id: document.getElementById('dashSite')?.value || '',
            year: document.getElementById('dashYear')?.value || '',
            month: document.getElementById('dashMonth')?.value || '',
            department_id: document.getElementById('dashDept')?.value || '',
            status_id: document.getElementById('dashStatus')?.value || '',
            case_type_id: document.getElementById('dashType')?.value || ''

        };

    },

    load() {

        const params = new URLSearchParams(this.getFilter());

        fetch(APP.baseUrl + 'dashboard/summary?' + params.toString())

            .then(res => res.json())

            .then(data => {

                console.log(data);

                this.renderSummary(data.summary);

                this.renderTrend(data.trend);

                this.renderDepartment(data.department);

                this.renderCaseType(data.case_type);

                if (data.satisfaction) {

                    this.renderSatisfaction(data.satisfaction);

                }

                this.renderRecent(data.recent);

                this.renderOverdue(data.overdue);

            })

            .catch(console.error);

    },

    bindEvent() {

        [

            'dashSite',
            'dashYear',
            'dashMonth',
            'dashDept',
            'dashStatus',
            'dashType'

        ].forEach(id => {

            const el = document.getElementById(id);

            if (!el) return;

            el.addEventListener('change', () => {

                this.load();

            });

        });

    },

    renderSummary(summary) {

        const cards = [

            {
                title: 'Total Cases',
                value: summary.total,
                class: 'total',
                icon: 'bi-folder2-open'
            },

            {
                title: 'Open',
                value: summary.open,
                class: 'open',
                icon: 'bi-exclamation-circle'
            },

            {
                title: 'In Progress',
                value: summary.progress,
                class: 'progress',
                icon: 'bi-arrow-repeat'
            },

            {
                title: 'Closed',
                value: summary.closed,
                class: 'closed',
                icon: 'bi-check-circle'
            },

            {
                title: 'Overdue',
                value: summary.overdue,
                class: 'overdue',
                icon: 'bi-clock-history'
            },

            {
                title: 'Avg Response',
                value: summary.response,
                class: 'response',
                icon: 'bi-stopwatch'
            }

        ];

        let html = '';

        cards.forEach(item => {

            html += `
            <div class="kpi ${item.class} card">

                <div class="kpi-icon">

                    <i class="bi ${item.icon}"></i>

                </div>

                <small>${item.title}</small>

                <h2>${item.value}</h2>

            </div>
            `;

        });

        document.getElementById('kpiGrid').innerHTML = html;

    },

    renderTrend(data) {

        const canvas = document.getElementById('trendChart');

        if (!canvas) return;

        if (this.chart.trend) {

            this.chart.trend.destroy();

        }

        this.chart.trend = new Chart(canvas, {

            type: 'line',

            data: {

                labels: [
                    'Jan', 'Feb', 'Mar', 'Apr',
                    'May', 'Jun', 'Jul', 'Aug',
                    'Sep', 'Oct', 'Nov', 'Dec'
                ],

                datasets: [{

                    data: data,

                    fill: true,

                    tension: .35,

                    borderWidth: 2

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                }

            }

        });

    },

    renderDepartment(data) {

        const canvas = document.getElementById('departmentChart');

        if (!canvas) return;

        if (this.chart.department) {

            this.chart.department.destroy();

        }

        this.chart.department = new Chart(canvas, {

            type: 'bar',

            data: {

                labels: data.labels,

                datasets: [{

                    data: data.data

                }]

            },

            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                }

            }

        });

    },

    renderCaseType(data) {

        const canvas = document.getElementById('caseTypeChart');

        if (!canvas) return;

        if (this.chart.caseType) {

            this.chart.caseType.destroy();

        }

        this.chart.caseType = new Chart(canvas, {

            type: 'doughnut',

            data: {

                labels: data.labels,

                datasets: [{

                    data: data.data

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false

            }

        });

    },

    renderSatisfaction(data) {

        const canvas = document.getElementById('satisfactionChart');

        if (!canvas) return;

        if (this.chart.satisfaction) {

            this.chart.satisfaction.destroy();

        }

        this.chart.satisfaction = new Chart(canvas, {

            type: 'bar',

            data: {

                labels: data.labels,

                datasets: [{

                    data: data.data

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                }

            }

        });

    },

    renderRecent(data) {

        const tbody = document.getElementById('recentBody');

        if (!tbody) return;

        let html = '';

        if (!data.length) {

            html = `
            <tr>

                <td colspan="6" class="text-center">

                    No data

                </td>

            </tr>
            `;

        } else {

            data.forEach(item => {

                const badge = item.status
                    .toLowerCase()
                    .replaceAll(' ', '-');

                html += `
                <tr>

                    <td>${item.case_number}</td>

                    <td>${item.department}</td>

                    <td>${item.case_type}</td>

                    <td>

                        <span class="badge badge-${badge}">

                            ${item.status}

                        </span>

                    </td>

                    <td>${item.pic ?? '-'}</td>

                    <td>${item.target_closure_date}</td>

                </tr>
                `;

            });

        }

        tbody.innerHTML = html;

    },

    renderOverdue(data) {

        const list = document.getElementById('alertList');

        if (!list) return;

        let html = '';

        if (!data.length) {

            html = '<p class="text-center">No overdue cases</p>';

        } else {

            data.forEach(item => {

                html += `

                <div class="alert-item">

                    <div>

                        <strong>${item.case_number}</strong><br>

                        ${item.department}

                    </div>

                    <span class="badge badge-overdue">

                        ${item.days} Days

                    </span>

                </div>

                `;

            });

        }

        list.innerHTML = html;

    }

};

document.addEventListener('DOMContentLoaded', () => {

    Dashboard.init();

});
document
.getElementById('resetDash')
.addEventListener('click',()=>{

    [

        'dashSite',

        'dashYear',

        'dashMonth',

        'dashDept',

        'dashStatus',

        'dashType'

    ].forEach(id=>{

        document.getElementById(id).value='';

    });

    Dashboard.load();

});
(function () {

    const isAdmin = document.getElementById('dashSite') !== null;

    let charts = {
        trend: null,
        department: null,
        caseType: null,
        satisfaction: null,
    };

    const monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    const palette = ['#5e72e4', '#11cdef', '#2dce89', '#fb6340', '#f5365c', '#fbb140', '#324cdd', '#8898aa'];

    function fmtDate(s) {
        if (!s) return '-';
        const d = new Date(s + 'T00:00:00');
        return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function statusPillClass(status) {
        const map = {
            'Open': 'status-open',
            'In Progress': 'status-progress',
            'Closed': 'status-closed',
            'Overdue': 'status-overdue',
        };
        return map[status] || 'status-open';
    }

    function buildQuery() {
        const params = new URLSearchParams();

        if (isAdmin) {
            const site = document.getElementById('dashSite').value;
            if (site) params.set('site_id', site);
        }

        const year = document.getElementById('dashYear').value;
        const month = document.getElementById('dashMonth').value;
        const status = document.getElementById('dashStatus').value;
        const type = document.getElementById('dashType').value;
        const dept = document.getElementById('dashDept').value;

        if (year) params.set('year', year);
        if (month) params.set('month', month);
        if (status) params.set('status_id', status);
        if (type) params.set('case_type_id', type);
        if (dept) params.set('department_id', dept);

        return params.toString();
    }

    function renderKpis(summary) {
        const cards = [
            ['Total Cases', summary.total, 'bi-clipboard-data', 'linear-gradient(135deg,#5e72e4,#324cdd)'],
            ['Open', summary.open, 'bi-exclamation-circle', 'linear-gradient(135deg,#fb6340,#fbb140)'],
            ['In Progress', summary.progress, 'bi-hourglass-split', 'linear-gradient(135deg,#11cdef,#1171ef)'],
            ['Closed', summary.closed, 'bi-check-circle', 'linear-gradient(135deg,#2dce89,#2dcecc)'],
            ['Overdue', summary.overdue, 'bi-alarm', 'linear-gradient(135deg,#f5365c,#f56036)'],
        ];

        document.getElementById('kpiGrid').innerHTML = cards.map(k => `
            <div class="kpi card" style="--tone:${k[3]}">
                <div>
                    <div class="kpi-label">${k[0]}</div>
                    <div class="kpi-value">${k[1]}</div>
                </div>
                <div class="kpi-icon"><i class="bi ${k[2]}"></i></div>
            </div>
        `).join('');
    }

    function renderTrend(trend, year) {
        document.getElementById('trendYearLabel').textContent = year || 'This Year';

        const ctx = document.getElementById('trendChart');

        if (charts.trend) charts.trend.destroy();

        charts.trend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Cases',
                    data: trend,
                    borderColor: '#5e72e4',
                    backgroundColor: 'rgba(94,114,228,.08)',
                    fill: true,
                    tension: .35,
                    pointRadius: 3,
                    pointBackgroundColor: '#5e72e4',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f0f2f7' } },
                    x: { grid: { display: false } },
                }
            }
        });
    }

    function renderDepartment(department) {
        const ctx = document.getElementById('departmentChart');

        if (charts.department) charts.department.destroy();

        charts.department = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: department.labels,
                datasets: [{
                    data: department.data,
                    backgroundColor: '#5e72e4',
                    borderRadius: 6,
                    barThickness: 16,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f0f2f7' } },
                    y: { grid: { display: false } },
                }
            }
        });
    }

    function renderCaseType(caseType) {
        const ctx = document.getElementById('caseTypeChart');

        if (charts.caseType) charts.caseType.destroy();

        if (!caseType.labels.length) {
            ctx.getContext('2d').clearRect(0, 0, ctx.width, ctx.height);
            return;
        }

        charts.caseType = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: caseType.labels,
                datasets: [{
                    data: caseType.data,
                    backgroundColor: palette,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 8, font: { size: 10 } }
                    }
                }
            }
        });
    }

    function renderSatisfaction(satisfaction) {
        const ctx = document.getElementById('satisfactionChart');

        if (charts.satisfaction) charts.satisfaction.destroy();

        charts.satisfaction = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: satisfaction.labels,
                datasets: [{
                    data: satisfaction.data,
                    backgroundColor: '#2dce89',
                    borderRadius: 6,
                    barThickness: 28,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f0f2f7' } },
                    x: { grid: { display: false } },
                }
            }
        });
    }

    function renderRecent(recent) {
        const body = document.getElementById('recentBody');

        if (!recent.length) {
            body.innerHTML = `<tr><td colspan="6" class="empty-state">No cases found.</td></tr>`;
            return;
        }

        body.innerHTML = recent.map(c => `
            <tr>
                <td><a href="${APP.baseUrl}case/case-detail/${c.id ?? ''}" style="color:var(--su-primary);font-weight:700;text-decoration:none">${c.case_number}</a></td>
                <td>${c.department ?? '-'}</td>
                <td>${c.case_type ?? '-'}</td>
                <td><span class="status ${statusPillClass(c.status)}">${c.status ?? '-'}</span></td>
                <td>${c.pic ?? '-'}</td>
                <td>${fmtDate(c.target_closure_date)}</td>
            </tr>
        `).join('');
    }

    function renderAlerts(overdue) {
        document.getElementById('overdueBadge').textContent = overdue.length;

        const list = document.getElementById('alertList');

        if (!overdue.length) {
            list.innerHTML = `<div class="empty-state">No overdue cases.</div>`;
            return;
        }

        list.innerHTML = overdue.map(o => `
            <div class="alert-row">
                <div>
                    <strong>${o.case_number}</strong>
                    <span>${o.department ?? '-'}</span>
                </div>
                <span class="alert-due">${o.days} day${o.days != 1 ? 's' : ''} overdue</span>
            </div>
        `).join('');
    }

    function loadDashboard() {
        const qs = buildQuery();

        fetch(`${APP.baseUrl}dashboard/summary?${qs}`)
            .then(res => res.json())
            .then(data => {
                renderKpis(data.summary);
                renderTrend(data.trend, document.getElementById('dashYear').value);
                renderDepartment(data.department);
                renderCaseType(data.case_type);
                renderSatisfaction(data.satisfaction);
                renderRecent(data.recent);
                renderAlerts(data.overdue);
            })
            .catch(err => {
                console.error('Failed to load dashboard summary', err);
            });
    }

    function bindFilters() {
        const ids = ['dashYear', 'dashMonth', 'dashStatus', 'dashType', 'dashDept'];
        if (isAdmin) ids.unshift('dashSite');

        ids.forEach(id => document.getElementById(id).addEventListener('change', loadDashboard));

        document.getElementById('resetDash').addEventListener('click', () => {
            if (isAdmin) document.getElementById('dashSite').value = '';
            document.getElementById('dashYear').value = new Date().getFullYear();
            document.getElementById('dashMonth').value = '';
            document.getElementById('dashStatus').value = '';
            document.getElementById('dashType').value = '';
            document.getElementById('dashDept').value = '';
            loadDashboard();
        });
    }

    bindFilters();
    loadDashboard();

})();