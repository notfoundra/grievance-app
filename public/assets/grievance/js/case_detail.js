(function () {

    if (window.__caseDetailJsLoaded) return;
    window.__caseDetailJsLoaded = true;

    // ---------- Modal helpers ----------
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    document.getElementById('btnOpenEdit').addEventListener('click', () => openModal('modalEdit'));
    document.getElementById('btnOpenFollowUp').addEventListener('click', () => openModal('modalFollowUp'));

    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => closeModal(btn.dataset.close));
    });

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', e => {
            if (e.target === overlay) closeModal(overlay.id);
        });
    });

    // ---------- Rating stars ----------
    const ratingInput = document.getElementById('ratingInput');
    const ratingValue = document.getElementById('ratingValue');

    if (ratingInput) {
        ratingInput.querySelectorAll('i').forEach(star => {
            star.addEventListener('click', () => {
                const value = Number(star.dataset.value);
                ratingValue.value = value;

                ratingInput.querySelectorAll('i').forEach(s => {
                    const active = Number(s.dataset.value) <= value;
                    s.classList.toggle('bi-star-fill', active);
                    s.classList.toggle('bi-star', !active);
                    s.classList.toggle('active', active);
                });
            });
        });
    }

    // ---------- Submit Edit ----------
    document.getElementById('formEdit').addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;

        const fd = new FormData(e.target);

        fetch(`${APP.baseUrl}case/case-detail/${CASE_ID}/update`, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;

                if (data.status) {
                    Swal.fire({ icon: 'success', title: 'Case updated', timer: 1200, showConfirmButton: false })
                        .then(() => window.location.reload());
                } else {
                    const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal menyimpan perubahan.';
                    Swal.fire({ icon: 'error', title: 'Gagal', html: errors });
                }
            })
            .catch(() => {
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
            });
    });

    // ---------- Submit Follow Up ----------
    document.getElementById('formFollowUp').addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';

        const fd = new FormData(e.target);

        fetch(`${APP.baseUrl}case/case-detail/${CASE_ID}/follow-up`, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send"></i> Submit Follow Up';

                if (data.status) {
                    Swal.fire({ icon: 'success', title: 'Follow up added', timer: 1200, showConfirmButton: false })
                        .then(() => window.location.reload());
                } else {
                    const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal menambahkan follow up.';
                    Swal.fire({ icon: 'error', title: 'Gagal', html: errors });
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send"></i> Submit Follow Up';
                Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
            });
    });
    // ---------- Auto-open Follow Up modal kalau datang dari Follow Up Board ----------
    const params = new URLSearchParams(window.location.search);
    if (params.get('followup') === '1') {
        openModal('modalFollowUp');
    }
        // ---------- Delete Case ----------
    const btnDelete = document.getElementById('btnDeleteCase');

    if (btnDelete) {
        btnDelete.addEventListener('click', () => {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Case Ini?',
                html: `
                    Case ini akan disembunyikan dari Case Log, Dashboard, dan Follow Up Board.<br><br>
                    Data tetap tersimpan di database untuk keperluan audit dan dapat dipulihkan
                    oleh administrator sistem bila diperlukan.
                `,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f5365c',
                reverseButtons: true,
            }).then(result => {
                if (! result.isConfirmed) return;

                btnDelete.disabled = true;
                btnDelete.innerHTML = '<i class="bi bi-hourglass-split"></i> Menghapus...';

                fetch(`${APP.baseUrl}case/case-detail/${CASE_ID}/delete`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({ icon: 'success', title: 'Case dihapus', timer: 1200, showConfirmButton: false })
                                .then(() => {
                                    window.location.href = `${APP.baseUrl}grievance/case-log`;
                                });
                        } else {
                            btnDelete.disabled = false;
                            btnDelete.innerHTML = '<i class="bi bi-trash"></i> Delete Case';
                            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Tidak dapat menghapus case.' });
                        }
                    })
                    .catch(() => {
                        btnDelete.disabled = false;
                        btnDelete.innerHTML = '<i class="bi bi-trash"></i> Delete Case';
                        Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
                    });
            });
        });
    }
})();