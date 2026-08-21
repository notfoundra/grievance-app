(function () {
    const baseUrl = 'http://voice.kahatex-cj.local/user'; // Sesuaikan dengan baseUrl aplikasimu

    // Inisialisasi DataTable
    let table = $('#tableUsers').DataTable({
        ajax: `${baseUrl}/getData`,
        processing: true,
        serverSide: false,
        responsive: true
    });

    // ---------- Modal Helpers ----------
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    // Close button trigger
    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => closeModal(btn.dataset.close));
    });

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) closeModal(overlay.id);
        });
    });

    let saveMethod = 'add';

    // ---------- Action: Open Add ----------
    document.getElementById('btnOpenAdd').addEventListener('click', () => {
        saveMethod = 'add';
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('modalTitle').innerText = 'Add New User';
        document.getElementById('passwordHelp').style.display = 'none';
        document.getElementById('password').setAttribute('required', 'true');
        openModal('modalUser');
    });

    // ---------- Action: Open Edit (Delegation for DataTable) ----------
    $('#tableUsers tbody').on('click', '.btn-edit', function () {
        saveMethod = 'edit';
        let id = $(this).data('id');
        
        document.getElementById('userForm').reset();
        document.getElementById('modalTitle').innerText = 'Edit User';
        document.getElementById('passwordHelp').style.display = 'block';
        document.getElementById('password').removeAttribute('required');

        fetch(`${baseUrl}/edit/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('userId').value = data.id;
                document.getElementById('name').value = data.name;
                document.getElementById('username').value = data.username;
                document.getElementById('email').value = data.email;
                document.getElementById('role').value = data.role;
                openModal('modalUser');
            });
    });

    // ---------- Submit Form Add/Edit ----------
    document.getElementById('userForm').addEventListener('submit', function (e) {
        e.preventDefault();
        
        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = 'Saving...';

        const id = document.getElementById('userId').value;
        const url = saveMethod === 'add' ? `${baseUrl}/store` : `${baseUrl}/update/${id}`;
        const fd = new FormData(this);

        fetch(url, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = 'Save changes';

            if (data.status) {
                closeModal('modalUser');
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false });
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Failed to save.';
                Swal.fire({ icon: 'error', title: 'Error', html: errors });
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = 'Save changes';
            Swal.fire({ icon: 'error', title: 'Connection Error' });
        });
    });

    // ---------- Action: Toggle Status Active ----------
    $('#tableUsers tbody').on('change', '.toggle-active', function () {
        const id = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;

        fetch(`${baseUrl}/toggleStatus/${id}`, {
            method: 'POST',
            body: JSON.stringify({ is_active: isActive }),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                // Toast alert atau biarkan silent update
                Swal.fire({ icon: 'success', title: 'Status Updated!', toast: true, position: 'top-end', timer: 1500, showConfirmButton: false });
            }
        });
    });

    // ---------- Action: Delete ----------
    $('#tableUsers tbody').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${baseUrl}/delete/${id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        table.ajax.reload(null, false);
                        Swal.fire('Deleted!', data.message, 'success');
                    }
                });
            }
        });
    });

})();