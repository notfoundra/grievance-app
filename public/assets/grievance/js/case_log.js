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