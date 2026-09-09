(function () {

    if (window.__quisionerJsLoaded) return;
    window.__quisionerJsLoaded = true;

    // ================= IMPORT MODAL (selalu di-bind, walau belum ada data) =================

    const btnOpenImport = document.getElementById('btnOpenImportQuiz');
    const modalImport    = document.getElementById('modalImportQuiz');
    const formImport      = document.getElementById('formImportQuiz');
    const dropArea         = document.getElementById('quizDropArea');
    const fileInput          = document.getElementById('quizFileInput');
    const fileInfo             = document.getElementById('quizFileInfo');
    const fileName              = document.getElementById('quizFileName');
    const btnSubmitImport        = document.getElementById('btnSubmitImportQuiz');

    let selectedFile = null;

    function openModal(el) { el.classList.add('open'); }
    function closeModal(el) { el.classList.remove('open'); }

    if (btnOpenImport) {
        btnOpenImport.addEventListener('click', () => openModal(modalImport));
    }

    if (modalImport) {
        modalImport.querySelectorAll('[data-close]').forEach(btn => {
            btn.addEventListener('click', () => closeModal(modalImport));
        });

        modalImport.addEventListener('click', e => {
            if (e.target === modalImport) closeModal(modalImport);
        });
    }

    function setFile(file) {
        selectedFile = file;
        fileName.textContent = file.name;
        fileInfo.style.display = 'block';
        btnSubmitImport.disabled = false;
    }

    if (dropArea) {
        dropArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', e => {
            if (e.target.files[0]) setFile(e.target.files[0]);
        });

        ['dragenter', 'dragover'].forEach(evt => {
            dropArea.addEventListener(evt, e => { e.preventDefault(); dropArea.classList.add('dragover'); });
        });

        ['dragleave', 'drop'].forEach(evt => {
            dropArea.addEventListener(evt, e => { e.preventDefault(); dropArea.classList.remove('dragover'); });
        });

        dropArea.addEventListener('drop', e => {
            e.preventDefault();
            if (e.dataTransfer.files[0]) setFile(e.dataTransfer.files[0]);
        });
    }

    const btnRemoveFile = document.getElementById('btnRemoveQuizFile');
    if (btnRemoveFile) {
        btnRemoveFile.addEventListener('click', () => {
            selectedFile = null;
            fileInput.value = '';
            fileInfo.style.display = 'none';
            btnSubmitImport.disabled = true;
        });
    }

          if (formImport) {
        formImport.addEventListener('submit', function (e) {
            e.preventDefault();

            if (! selectedFile) return;

            btnSubmitImport.disabled = true;
            btnSubmitImport.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

            const fd = new FormData(formImport);

            fetch(`${APP.baseUrl}grievance/quisioner/import`, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(res => res.json())
                .then(data => {
                    btnSubmitImport.disabled = false;
                    btnSubmitImport.innerHTML = '<i class="bi bi-upload"></i> Import';

                    if (! data.status) {
                        const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'Gagal import.');
                        Swal.fire({ icon: 'error', title: 'Gagal', html: errors });
                        return;
                    }

                    showImportResult(data.result);
                })
                .catch(() => {
                    btnSubmitImport.disabled = false;
                    btnSubmitImport.innerHTML = '<i class="bi bi-upload"></i> Import';
                    Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
                });
        });
    }

    function showImportResult(result) {
        const hasErrors = result.errors && result.errors.length > 0;
        const hasDuplicates = result.skipped_duplicate > 0;

        if (! hasErrors && ! hasDuplicates) {
            Swal.fire({
                icon: 'success',
                title: 'Import selesai',
                text: `${result.created} peserta berhasil diimport.`,
            }).then(() => {
                window.location.href = `${APP.baseUrl}grievance/quisioner?selected=${result.master_id}`;
            });
            return;
        }

        let html = `
            <p style="font-size:.85rem;margin-bottom:1rem">
                <strong>${result.created}</strong> peserta berhasil diimport`;

        if (hasDuplicates) {
            html += `, <strong style="color:#fb6340">${result.skipped_duplicate}</strong> dilewati (nama sudah ada)`;
        }

        if (hasErrors) {
            html += `, <strong style="color:#f5365c">${result.errors.length}</strong> baris gagal`;
        }

        html += `.</p>`;

        if (hasErrors) {
            const rowsHtml = result.errors.map(e => `
                <tr>
                    <td style="padding:.4rem .6rem;border-bottom:1px solid #f0f2f7;font-weight:700;white-space:nowrap">Baris ${e.row}</td>
                    <td style="padding:.4rem .6rem;border-bottom:1px solid #f0f2f7;text-align:left">${e.reason}</td>
                </tr>
            `).join('');

            html += `
                <div style="max-height:260px;overflow-y:auto;border:1px solid #f0f2f7;border-radius:.5rem">
                    <table style="width:100%;border-collapse:collapse;font-size:.75rem">
                        <thead>
                            <tr style="background:#f8f9fe">
                                <th style="padding:.5rem .6rem;text-align:left">Baris</th>
                                <th style="padding:.5rem .6rem;text-align:left">Alasan</th>
                            </tr>
                        </thead>
                        <tbody>${rowsHtml}</tbody>
                    </table>
                </div>
            `;
        }

        Swal.fire({
            icon: hasErrors ? 'warning' : 'success',
            title: hasErrors ? 'Import selesai dengan beberapa error' : 'Import selesai',
            width: 560,
            html,
            confirmButtonText: 'Lanjut ke Hasil Import',
        }).then(() => {
            window.location.href = `${APP.baseUrl}grievance/quisioner?selected=${result.master_id}`;
        });
    }

    // ================= ADD QUISIONER MODAL =================

    const btnOpenAddQuiz = document.getElementById('btnOpenAddQuiz');
    const modalAddQuiz    = document.getElementById('modalAddQuiz');
    const formAddQuiz      = document.getElementById('formAddQuiz');

    if (btnOpenAddQuiz) {
        btnOpenAddQuiz.addEventListener('click', () => openModal(modalAddQuiz));
    }

    if (modalAddQuiz) {
        modalAddQuiz.querySelectorAll('[data-close]').forEach(btn => {
            btn.addEventListener('click', () => closeModal(modalAddQuiz));
        });

        modalAddQuiz.addEventListener('click', e => {
            if (e.target === modalAddQuiz) closeModal(modalAddQuiz);
        });
    }

    if (formAddQuiz) {
        formAddQuiz.addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = document.getElementById('btnSubmitAddQuiz');
            btn.disabled = true;

            const fd = new FormData(formAddQuiz);

            fetch(`${APP.baseUrl}grievance/quisioner/store`, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        window.location.href = `${APP.baseUrl}grievance/quisioner?selected=${data.master_id}`;
                    } else {
                        btn.disabled = false;
                        const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Gagal menyimpan.';
                        Swal.fire({ icon: 'error', title: 'Gagal', html: errors });
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
                });
        });
    }

    function showImportResult(result) {
        const hasErrors = result.errors && result.errors.length > 0;

        if (! hasErrors) {
            Swal.fire({
                icon: 'success',
                title: 'Import selesai',
                text: `${result.created} peserta berhasil diimport.`,
            }).then(() => {
                window.location.href = `${APP.baseUrl}grievance/quisioner?selected=${result.master_id}`;
            });
            return;
        }

        // Ada baris yang gagal — tampilkan detail row + alasan, jangan cuma diam-diam di-skip.
        const rowsHtml = result.errors.map(e => `
            <tr>
                <td style="padding:.4rem .6rem;border-bottom:1px solid #f0f2f7;font-weight:700;white-space:nowrap">Baris ${e.row}</td>
                <td style="padding:.4rem .6rem;border-bottom:1px solid #f0f2f7;text-align:left">${e.reason}</td>
            </tr>
        `).join('');

        Swal.fire({
            icon: 'warning',
            title: 'Import selesai dengan beberapa error',
            width: 560,
            html: `
                <p style="font-size:.85rem;margin-bottom:1rem">
                    <strong>${result.created}</strong> peserta berhasil diimport,
                    <strong style="color:#f5365c">${result.errors.length}</strong> baris gagal.
                </p>
                <div style="max-height:260px;overflow-y:auto;border:1px solid #f0f2f7;border-radius:.5rem">
                    <table style="width:100%;border-collapse:collapse;font-size:.75rem">
                        <thead>
                            <tr style="background:#f8f9fe">
                                <th style="padding:.5rem .6rem;text-align:left">Baris</th>
                                <th style="padding:.5rem .6rem;text-align:left">Alasan</th>
                            </tr>
                        </thead>
                        <tbody>${rowsHtml}</tbody>
                    </table>
                </div>
            `,
            confirmButtonText: 'Lanjut ke Hasil Import',
        }).then(() => {
            window.location.href = `${APP.baseUrl}grievance/quisioner?selected=${result.master_id}`;
        });
    }

    // ================= CHART & TABLE (hanya jalan kalau sudah ada data) =================

    const select = document.getElementById('quizSelect');
    const selectDate = document.getElementById('selectDate');

    if (!select || !select.value) return;

    function destroyIfExists(canvasEl) {
        const existing = Chart.getChart(canvasEl);
        if (existing) existing.destroy();
    }
    function renderPassChart(summary) {
        const canvas = document.getElementById('quizPassChart');
        destroyIfExists(canvas);

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Lulus', 'Tidak Lulus'],
                datasets: [{
                    data: [summary.lulus, summary.tidak_lulus],
                    backgroundColor: ['#2dce89', '#f5365c'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } }
                }
            }
        });
    }

        const distColors = ['#f5365c', '#fb6340', '#fbb140', '#2dce89', '#11cdef'];
    // 0-59=merah, 60-69=oranye, 70-79=kuning, 80-89=hijau, 90-100=biru — makin ke kanan makin baik

    function renderDistributionChart(canvasId, distribution) {
        const canvas = document.getElementById(canvasId);
        destroyIfExists(canvas);

        new Chart(canvas, {
            type: 'pie',
            data: {
                labels: distribution.labels,
                datasets: [{
                    data: distribution.data,
                    backgroundColor: distColors,
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.label}: ${ctx.parsed} orang`
                        }
                    }
                }
            }
        });
    }

    function renderTable(participants, passingScore) {
    const body = document.getElementById('quizTableBody');
    document.getElementById('quizTableCount').textContent = `${participants.length} peserta`;

    if (! participants.length) {
        body.innerHTML = `<tr><td colspan="7" class="empty-state">Belum ada peserta untuk quisioner ini.</td></tr>`;
        return;
    }

    body.innerHTML = participants.map(p => {
        const pre = Number(p.pretest);
        const post = Number(p.posttest);
        const delta = post - pre;
        const lulus = post >= passingScore;
        const genderLabel = p.gender === 'L' ? 'Laki-laki' : (p.gender === 'P' ? 'Perempuan' : '-');

        return `
            <tr>
                <td><strong>${p.name}</strong></td>
                <td>${genderLabel}</td>
                <td>${pre}</td>
                <td>${post}</td>
                <td style="color:${delta >= 0 ? 'var(--su-success)' : 'var(--su-danger)'};font-weight:700">
                    ${delta >= 0 ? '+' : ''}${delta}
                </td>
                <td>${p.keterangan || '-'}</td>
                <td><span class="status ${lulus ? 'status-closed' : 'status-overdue'}">${lulus ? 'Lulus' : 'Tidak Lulus'}</span></td>
            </tr>
        `;
    }).join('');
}
    function renderGenderChart(distribution) {
    const canvas = document.getElementById('quizGenderChart');
    destroyIfExists(canvas);

    new Chart(canvas, {
        type: 'pie',
        data: {
            labels: distribution.labels,
            datasets: [{
                data: distribution.data,
                backgroundColor: ['#5e72e4', '#fb6340', '#8898aa'], // Laki-laki, Perempuan, Tidak Diketahui
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.label}: ${ctx.parsed} orang`
                    }
                }
            }
        }
    });
}

   function loadDates(masterId, callback) {
        fetch(`${APP.baseUrl}grievance/quisioner/getAvailableDates/${masterId}`)
            .then(res => res.json())
            .then(data => {
                // Bersihkan select dan beri opsi default "Semua Tanggal"
                selectDate.innerHTML = '<option value="">Semua Tanggal</option>';
                
                if (data.status && data.dates.length > 0) {
                    data.dates.forEach(date => {
                        const option = document.createElement('option');
                        option.value = date;
                        option.textContent = date;
                        selectDate.appendChild(option);
                    });
                }

                // Setelah tanggal sukses dimuat, jalankan callback (load data chart)
                if (callback) callback();
            })
            .catch(() => {
                selectDate.innerHTML = '<option value="">Semua Tanggal</option>';
                if (callback) callback();
            });
    }

    // 2. Fungsi memuat data Chart & Table
   function loadData() {
        if (!select.value) return;

        const masterId = select.value;
        const tanggal = selectDate ? selectDate.value : '';

        const batchLabel = select.options[select.selectedIndex].text;
        const dateInfo = tanggal ? ` (Tanggal: ${tanggal})` : '';
        document.getElementById('quizBatchLabel').textContent = batchLabel + dateInfo;

        let url = `${APP.baseUrl}grievance/quisioner/data/${masterId}`;
        if (tanggal) {
            url += `?tanggal=${tanggal}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                // Render Chart & Table
                renderPassChart(data.summary);
                renderGenderChart(data.gender_distribution);
                renderDistributionChart('quizPretestChart', data.pretest_distribution);
                renderDistributionChart('quizPosttestChart', data.posttest_distribution);
                renderTable(data.participants, data.passing_score);
                
                // ========================================================
                // TAMBAHAN: UPDATE KARTU KPI
                // ========================================================
                
                // Hitung persen kelulusan
                let passRate = 0;
                if (data.summary.total > 0) {
                    passRate = ((data.summary.lulus / data.summary.total) * 100).toFixed(1);
                }
                
                // Tentukan angka Total Quisioner (Sesi)
                // Jika tanggal difilter, nilainya 1. Jika tidak, total sesi = jumlah isi dropdown dikurangi 1 (opsi "Semua Tanggal")
                let totalSesi = tanggal ? 1 : (selectDate.options.length - 1);
                if (totalSesi < 0) totalSesi = 0;

                // Terapkan ke HTML berdasarkan ID
                document.getElementById('kpiTotalQuisioner').textContent = totalSesi;
                document.getElementById('kpiTotalPeserta').textContent = data.summary.total;
                document.getElementById('kpiPassRate').textContent = passRate + '%';
                document.getElementById('kpiPassingScore').innerHTML = '&ge; ' + data.passing_score;
                
                // ========================================================

                document.getElementById('quizAvgLabel').textContent =
                    `Rata-rata Pretest: ${data.summary.avg_pretest} · Posttest: ${data.summary.avg_posttest}`;
            })
            .catch(() => {
                document.getElementById('quizTableBody').innerHTML =
                    `<tr><td colspan="7" class="empty-state">Gagal memuat data.</td></tr>`;
            });
    }

    // 3. Event Listener
    if (select) {
        // Jika Quisioner diubah: load list tanggal dulu, setelah selesai baru load Data Chart
        select.addEventListener('change', () => {
            loadDates(select.value, loadData);
        });
    }

    if (selectDate) {
        // Jika hanya tanggalnya saja yang diubah, langsung load Data Chart
        selectDate.addEventListener('change', loadData);
    }

    // 4. Initial load saat halaman pertama kali dibuka
    loadDates(select.value, loadData);

})();