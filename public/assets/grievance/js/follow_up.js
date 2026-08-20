(function () {

    if (window.__followUpJsLoaded) return;
    window.__followUpJsLoaded = true;

    const isAdmin = document.getElementById('fbSite') !== null;

    const columns = [
        { key: 'Open',        label: 'Open',        dot: 'var(--su-warning)' },
        { key: 'In Progress', label: 'In Progress',  dot: 'var(--su-info)' },
        { key: 'Overdue',     label: 'Overdue',      dot: 'var(--su-danger)' },
        { key: 'Closed',      label: 'Closed',       dot: 'var(--su-success)' },
    ];

    function fmtDate(s) {
        if (!s) return '-';
        const d = new Date(s + 'T00:00:00');
        return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function isLate(dateStr) {
        if (!dateStr) return false;
        return dateStr < new Date().toISOString().slice(0, 10);
    }

    function priorityClass(p) {
        const map = { Urgent: 'priority-urgent', Medium: 'priority-medium', Low: 'priority-low' };
        return map[p] || 'priority-medium';
    }

    function buildQuery() {
        const params = new URLSearchParams();

        if (isAdmin) {
            const site = document.getElementById('fbSite').value;
            if (site) params.set('site_id', site);
        }

        const year = document.getElementById('fbYear').value;
        const dept = document.getElementById('fbDept').value;
        const type = document.getElementById('fbType').value;
        const priority = document.getElementById('fbPriority').value;
        const includeClosed = document.getElementById('fbIncludeClosed').checked;

        if (year) params.set('year', year);
        if (dept) params.set('department_id', dept);
        if (type) params.set('case_type_id', type);
        if (priority) params.set('priority_id', priority);
        if (includeClosed) params.set('include_closed', '1');

        return params.toString();
    }

    function renderCard(c) {
        const late = isLate(c.target_closure_date);

        return `
            <div class="case-card" data-id="${c.id}">
                <div class="cc-top">
                    <span class="cc-id">${c.case_number}</span>
                    <span class="priority ${priorityClass(c.priority)}">${c.priority || '-'}</span>
                </div>
                <div class="cc-tag">${c.department || '-'} · ${c.case_type || '-'}</div>
                <p>${c.message || ''}</p>
                <div class="cc-foot">
                    <span>${c.pic || 'Unassigned'}</span>
                    <span class="due ${late ? 'late' : ''}">
                        <i class="bi bi-calendar-event"></i> ${fmtDate(c.target_closure_date)}
                    </span>
                </div>
            </div>
        `;
    }

    function renderBoard(data) {
        const container = document.getElementById('followBoard');

        container.innerHTML = columns.map(col => {
            const items = data[col.key] || [];

            return `
                <div class="board-col">
                    <div class="board-head">
                        <h4><span class="dot" style="background:${col.dot}"></span>${col.label}</h4>
                        <span class="board-count">${items.length}</span>
                    </div>
                    <div class="board-list">
                        ${items.length ? items.map(renderCard).join('') : '<div class="board-empty">No cases</div>'}
                    </div>
                </div>
            `;
        }).join('');

        container.querySelectorAll('.case-card').forEach(card => {
            card.addEventListener('click', () => {
                window.location.href = `${APP.baseUrl}case/case-detail/${card.dataset.id}?followup=1`;
            });
        });
    }

    function loadBoard() {
        const qs = buildQuery();

        fetch(`${APP.baseUrl}grievance/follow-up-data?${qs}`)
            .then(res => res.json())
            .then(renderBoard)
            .catch(() => {
                document.getElementById('followBoard').innerHTML =
                    '<div class="board-empty">Failed to load data.</div>';
            });
    }

    function bind() {
        const ids = ['fbYear', 'fbDept', 'fbType', 'fbPriority', 'fbIncludeClosed'];
        if (isAdmin) ids.unshift('fbSite');

        ids.forEach(id => document.getElementById(id).addEventListener('change', loadBoard));

        document.getElementById('fbReset').addEventListener('click', () => {
            if (isAdmin) document.getElementById('fbSite').value = '';
            document.getElementById('fbYear').value = new Date().getFullYear();
            document.getElementById('fbDept').value = '';
            document.getElementById('fbType').value = '';
            document.getElementById('fbPriority').value = '';
            document.getElementById('fbIncludeClosed').checked = false;
            loadBoard();
        });
    }

    bind();
    loadBoard();

})();