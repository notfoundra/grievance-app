(function () {

    if (window.__masterDataJsLoaded) return;
    window.__masterDataJsLoaded = true;

    const labels = {
        'case-type':  'Case Type',
        'channel':    'Channel',
        'department': 'Department',
    };

    let currentType = 'case-type';
    let editingId    = null;

    const tabs   = document.querySelectorAll('#mdTabs .tabnav-item');
    const tbody  = document.getElementById('mdTableBody');
    const mdTitle = document.getElementById('mdTitle');
    const mdCount = document.getElementById('mdCount');

    function switchTab(type) {
        currentType = type;
        tabs.forEach(t => t.classList.toggle('active', t.dataset.type === type));
        mdTitle.textContent = labels[type];
        loadData();
    }

    tabs.forEach(tab => tab.addEventListener('click', () => switchTab(tab.dataset.type)));

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function renderRows(rows) {
        mdCount.textContent = `${rows.length} entries`;

        if (! rows.length) {
            tbody.innerHTML = `<tr><td colspan="4" class="empty-state">No data yet.</td></tr>`;
            return;
        }

        tbody.innerHTML = rows.map(r => `
            <tr>
                <td><strong>${escapeHtml(r.name)}</strong></td>
                <td class="desc-cell" title="${escapeHtml(r.description || '')}">${escapeHtml(r.description) || '-'}</td>
                <td>
                    <button type="button" class="switch ${Number(r.is_active) ? 'on' : ''}" data-id="${r.id}" title="Toggle active"></button>
                </td>
                <td>
                    <button type="button" class="btn btn-soft btn-icon btn-edit"
                        data-id="${r.id}"
                        data-name="${escapeHtml(r.name)}"
                        data-description="${escapeHtml(r.description || '')}"
                        title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        tbody.querySelectorAll('.switch').forEach(btn => {
            btn.addEventListener('click', () => toggleActive(btn.dataset.id, btn));
        });

        tbody.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => openEdit(btn.dataset.id, btn.dataset.name, btn.dataset.description));
        });
    }

    function loadData() {
        tbody.innerHTML = `<tr><td colspan="4" class="empty-state">Loading...</td></tr>`;

        fetch(`${APP.baseUrl}grievance/master-data/list/${currentType}`)
            .then(res => res.json())
            .then(data => renderRows(data.data || []))
            .catch(() => {
                tbody.innerHTML = `<tr><td colspan="4" class="empty-state">Failed to load data.</td></tr>`;
            });
    }

    function toggleActive(id, btn) {
        btn.disabled = true;

        fetch(`${APP.baseUrl}grievance/master-data/${currentType}/toggle/${id}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;

                if (data.status) {
                    btn.classList.toggle('on', Number(data.is_active) === 1);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal mengubah status' });
                }
            })
            .catch(() => {
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
            });
    }

    // ---------- Modal Add/Edit ----------
    const modal      = document.getElementById('modalMaster');
    const modalTitle  = document.getElementById('modalMasterTitle');
    const form        = document.getElementById('formMaster');
    const inputId      = document.getElementById('mdId');
    const inputName     = document.getElementById('mdName');
    const inputDescription = document.getElementById('mdDescription');

    function openModal() { modal.classList.add('open'); }
    function closeModal() { modal.classList.remove('open'); }

    document.querySelectorAll('[data-close="modalMaster"]').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    modal.addEventListener('click', e => {
        if (e.target === modal) closeModal();
    });

    document.getElementById('btnAddMaster').addEventListener('click', () => {
        editingId = null;
        form.reset();
        inputId.value = '';
        modalTitle.innerHTML = `<i class="bi bi-plus-circle"></i> Add ${labels[currentType]}`;
        openModal();
    });

    function openEdit(id, name, description) {
        editingId = id;
        inputId.value = id;
        inputName.value = name;
        inputDescription.value = description;
        modalTitle.innerHTML = `<i class="bi bi-pencil-square"></i> Edit ${labels[currentType]}`;
        openModal();
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;

        const fd = new FormData();
        fd.append('name', inputName.value.trim());
        fd.append('description', inputDescription.value.trim());

        const url = editingId
            ? `${APP.baseUrl}grievance/master-data/${currentType}/update/${editingId}`
            : `${APP.baseUrl}grievance/master-data/${currentType}/store`;

        fetch(url, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;

                if (data.status) {
                    closeModal();
                    loadData();
                    Swal.fire({ icon: 'success', title: data.message, timer: 1200, showConfirmButton: false });
                } else {
                    const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal menyimpan data.';
                    Swal.fire({ icon: 'error', title: 'Gagal', html: errors });
                }
            })
            .catch(() => {
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
            });
    });

    switchTab('case-type');

})();