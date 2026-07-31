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