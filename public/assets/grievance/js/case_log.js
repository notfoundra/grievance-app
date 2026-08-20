const CaseLog = {

    table: null,

    init() {

        this.initTable();

        this.bind();

    },

    initTable() {

        this.table = $("#tableCases").DataTable({

            processing: true,

            responsive: true,

            pageLength: 10,

            order: [[1, "desc"]],

            ajax: {

                url: APP.baseUrl + "case/ajax-list",

                type: "POST",

                dataSrc: ""

            },

            columns: [

                {
                    data: "case_number"
                },

                {
                    data: "received_date"
                },

                {
                    data: "department"
                },

                {
    data: "message",
    render: function (data, type) {

        if (type === "display") {

            if (!data) return "-";

            const shortText = data.length > 50
                ? data.substring(0, 50) + "..."
                : data;

            return `<span title="${data.replace(/"/g, '&quot;')}">${shortText}</span>`;
        }

        return data;
    }
},

              {
    data: "priority",
    render: function(data){

        let cls="priority-medium";

        if(data==="Urgent")
            cls="priority-urgent";

        if(data==="Low")
            cls="priority-low";

        return `<span class="priority ${cls}">${data}</span>`;

    }
},

               {
    data: "status",
    render: function (data) {

        let cls = "badge-status";

        switch (data) {

            case "Open":
                cls += " badge-open";
                break;

            case "In Progress":
                cls += " badge-progress";
                break;

            case "Closed":
                cls += " badge-closed";
                break;

            default:
                cls += " badge-overdue";

        }

        return `<span class="${cls}">${data}</span>`;

    }
},

                {
                    data: "pic",
                    defaultContent: "-"
                },

               {
    data:"target_closure_date",

    render:function(data){

        if(!data) return "-";

        const today=new Date();

        const due=new Date(data);

        if(due<today){

            return `<span class="overdue-date">${data}</span>`;

        }

        return data;

    }

},

               {
    data:"id",

    orderable:false,

    searchable:false,

    className:"text-center",

    render:function(id){

        return `
            <a href="${APP.baseUrl}case/case-detail/${id}"
               class="btn-action"
               title="View Detail">

                <i class="bi bi-eye"></i>

            </a>
        `;

    }

}

            ],

            language: {

                emptyTable: "No grievance cases found.",

                processing: "Loading..."

            }

        });

    },

    reload() {

        this.table.ajax.reload(null, false);

    },

    bind() {

        $("#searchCase").on("keyup", () => {

            this.table.search($("#searchCase").val()).draw();

        });

        $("#filterStatus").on("change", () => {

            this.table.column(5).search($("#filterStatus").val()).draw();

        });

        $("#filterDepartment").on("change", () => {

            this.table.column(2).search($("#filterDepartment").val()).draw();

        });

        $("#btnRefresh").on("click", () => {

            this.reload();

        });

    }

};

$(document).ready(function () {

    CaseLog.init();

});
(function () {

    if (window.__caseLogJsLoaded) return;
    window.__caseLogJsLoaded = true;

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

    function priorityClass(priority) {
        const map = {
            'Urgent': 'priority-urgent',
            'Medium': 'priority-medium',
            'Low': 'priority-low',
        };
        return map[priority] || 'priority-medium';
    }

    function truncate(text, len) {
        if (!text) return '-';
        return text.length > len ? text.slice(0, len) + '…' : text;
    }

    let table;

    function initTable(rows) {
        if ($.fn.DataTable.isDataTable('#tableCases')) {
            table = $('#tableCases').DataTable();
            table.clear();
            table.rows.add(mapRows(rows));
            table.draw();
            return;
        }

        table = $('#tableCases').DataTable({
            data: mapRows(rows),
            columns: [
                { data: 'case_number', render: (v, t, row) => `<a href="${APP.baseUrl}case/case-detail/${row.id}" class="case-link">${v}</a>` },
                { data: 'received_date', render: v => fmtDate(v) },
                { data: 'department', render: v => v || '-' },
                { data: 'message', render: v => truncate(v, 60) },
                { data: 'priority', render: v => `<span class="priority ${priorityClass(v)}">${v || '-'}</span>` },
                { data: 'status', render: v => `<span class="status ${statusPillClass(v)}">${v || '-'}</span>` },
                { data: 'pic', render: v => v || '-' },
                { data: 'target_closure_date', render: v => fmtDate(v) },
                {
                    data: null,
                    orderable: false,
                    render: (v, t, row) => `
                        <a href="${APP.baseUrl}case/case-detail/${row.id}" class="btn btn-soft btn-icon" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                    `
                },
            ],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[1, 'desc']],
            language: {
                emptyTable: 'No cases found.',
                zeroRecords: 'No matching cases found.',
            },
        });
    }

    function mapRows(rows) {
        return rows.map(r => ({
            id: r.id,
            case_number: r.case_number,
            received_date: r.received_date,
            department: r.department,
            message: r.message,
            priority: r.priority,
            status: r.status,
            pic: r.pic,
            target_closure_date: r.target_closure_date,
        }));
    }

    function applyFilters(rows) {
        const search = $('#searchCase').val().toLowerCase().trim();
        const dept = $('#filterDepartment').val();
        const priority = $('#filterPriority').val();
        const status = $('#filterStatus').val();
        const dateFrom = $('#dateFrom').val();
        const dateTo = $('#dateTo').val();

        return rows.filter(r => {
            if (dept && r.department !== dept) return false;
            if (priority && r.priority !== priority) return false;
            if (status && r.status !== status) return false;
            if (dateFrom && r.received_date < dateFrom) return false;
            if (dateTo && r.received_date > dateTo) return false;

            if (search) {
                const haystack = [r.case_number, r.pic, r.department, r.message]
                    .join(' ').toLowerCase();
                if (! haystack.includes(search)) return false;
            }

            return true;
        });
    }

    let allRows = [];

    function loadData() {
        $.ajax({
            url: `${APP.baseUrl}case/ajax-list`,
            method: 'POST',
        }).done(rows => {
            allRows = rows || [];
            initTable(applyFilters(allRows));
        }).fail(() => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal memuat data',
                text: 'Tidak dapat mengambil data case log. Silakan refresh halaman.',
            });
        });
    }

    function refilter() {
        if (table) {
            table.clear();
            table.rows.add(mapRows(applyFilters(allRows)));
            table.draw();
        }
    }

    function exportCSV() {
        const rows = applyFilters(allRows);

        if (! rows.length) {
            Swal.fire({ icon: 'info', title: 'Tidak ada data', text: 'Tidak ada data untuk diexport.' });
            return;
        }

        const headers = ['Case No', 'Received', 'Department', 'Case Type', 'Priority', 'Status', 'PIC', 'Due Date'];

        const lines = rows.map(r => [
            r.case_number, r.received_date, r.department, r.case_type,
            r.priority, r.status, r.pic, r.target_closure_date,
        ].map(v => `"${String(v ?? '').replace(/"/g, '""')}"`).join(','));

        const csv = [headers.join(','), ...lines].join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = `grievance_case_log_${new Date().toISOString().slice(0, 10)}.csv`;
        a.click();
        URL.revokeObjectURL(a.href);
    }

    function bind() {
        $('#searchCase').on('input', refilter);
        $('#filterDepartment, #filterPriority, #filterStatus, #dateFrom, #dateTo').on('change', refilter);
        $('#btnRefresh').on('click', loadData);
        $('#btnExport').on('click', exportCSV);
    }

    bind();
    loadData();

})();