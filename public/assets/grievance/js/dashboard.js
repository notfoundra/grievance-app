(function () {

    if (window.__dashboardJsLoaded) return;
    window.__dashboardJsLoaded = true;

    const isAdmin = document.getElementById('dashSite') !== null;

    let charts = {
        trend: null,
        department: null,
        caseType: null,
        satisfaction: null,
    };

    const palette = ['#5e72e4', '#11cdef', '#2dce89', '#fb6340', '#f5365c', '#fbb140', '#324cdd', '#8898aa'];

    function defaultDateFrom() {
        const d = new Date();
        d.setMonth(d.getMonth() - 5);
        d.setDate(1);
        return d.toISOString().slice(0, 10);
    }

    function defaultDateTo() {
        return new Date().toISOString().slice(0, 10);
    }

    function fmtDate(s) {
        if (!s) return '-';
        const d = new Date(s + 'T00:00:00');
        return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function fmtDateShort(s) {
        if (!s) return '';
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

    function destroyIfExists(canvasEl) {
        const existing = Chart.getChart(canvasEl);
        if (existing) existing.destroy();
    }

    function buildQuery() {
        const params = new URLSearchParams();

        if (isAdmin) {
            const site = document.getElementById('dashSite').value;
            if (site) params.set('site_id', site);
        }

        const dateFrom = document.getElementById('dashDateFrom').value;
        const dateTo = document.getElementById('dashDateTo').value;
        const status = document.getElementById('dashStatus').value;
        const type = document.getElementById('dashType').value;
        const dept = document.getElementById('dashDept').value;

        if (dateFrom) params.set('date_from', dateFrom);
        if (dateTo) params.set('date_to', dateTo);
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

    function renderTrend(trend, labels, dateFrom, dateTo) {
        document.getElementById('trendYearLabel').textContent =
            `${fmtDateShort(dateFrom)} – ${fmtDateShort(dateTo)}`;

        const canvas = document.getElementById('trendChart');
        destroyIfExists(canvas);

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
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
        const canvas = document.getElementById('departmentChart');
        destroyIfExists(canvas);

        new Chart(canvas, {
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
        console.log(caseType);
        const canvas = document.getElementById('caseTypeChart');
        destroyIfExists(canvas);

        if (!caseType.labels.length) {
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            return;
        }

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: caseType.label,
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
        const canvas = document.getElementById('satisfactionChart');
        destroyIfExists(canvas);

        new Chart(canvas, {
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
                console.log(data);
                renderTrend(
                    data.trend,
                    data.trend_labels,
                    document.getElementById('dashDateFrom').value,
                    document.getElementById('dashDateTo').value
                );
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
        const ids = ['dashDateFrom', 'dashDateTo', 'dashStatus', 'dashType', 'dashDept'];
        if (isAdmin) ids.unshift('dashSite');

        ids.forEach(id => document.getElementById(id).addEventListener('change', loadDashboard));

        document.getElementById('resetDash').addEventListener('click', () => {
            if (isAdmin) document.getElementById('dashSite').value = '';
            document.getElementById('dashDateFrom').value = defaultDateFrom();
            document.getElementById('dashDateTo').value = defaultDateTo();
            document.getElementById('dashStatus').value = '';
            document.getElementById('dashType').value = '';
            document.getElementById('dashDept').value = '';
            loadDashboard();
        });
    }

    function initDefaults() {
        document.getElementById('dashDateFrom').value = defaultDateFrom();
        document.getElementById('dashDateTo').value = defaultDateTo();
    }

    initDefaults();
    bindFilters();
    loadDashboard();

})();