(function () {

    if (window.__importJsLoaded) return;
    window.__importJsLoaded = true;

    const dropArea   = document.getElementById('dropArea');
    const fileInput  = document.getElementById('wovoFile');
    const fileInfo    = document.getElementById('fileInfo');
    const fileName    = document.getElementById('fileName');
    const btnImport   = document.getElementById('btnImport');
    const resultCard  = document.getElementById('resultCard');
    const resultSummary = document.getElementById('resultSummary');
    const resultErrors  = document.getElementById('resultErrors');

    let selectedFile = null;

    function setFile(file) {
        selectedFile = file;
        fileName.textContent = file.name;
        fileInfo.style.display = 'block';
        btnImport.disabled = false;
    }

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

    document.getElementById('btnRemoveFile').addEventListener('click', () => {
        selectedFile = null;
        fileInput.value = '';
        fileInfo.style.display = 'none';
        btnImport.disabled = true;
    });

    btnImport.addEventListener('click', () => {
        if (! selectedFile) return;

        btnImport.disabled = true;
        btnImport.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses... (bisa beberapa menit untuk file besar)';

        const fd = new FormData();
        fd.append('wovo_file', selectedFile);
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch(`${APP.baseUrl}grievance/import/process`, {
            method: 'POST',
            body: fd,
        })
            .then(res => res.json())
            .then(data => {
                btnImport.disabled = false;
                btnImport.innerHTML = '<i class="bi bi-upload"></i> Mulai Import';

                if (! data.status) {
                    Swal.fire({ icon: 'error', title: 'Import gagal', text: data.message });
                    return;
                }

                renderResult(data.result);
            })
            .catch(() => {
                btnImport.disabled = false;
                btnImport.innerHTML = '<i class="bi bi-upload"></i> Mulai Import';
                Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server' });
            });
    });

    function renderResult(result) {
        resultCard.style.display = 'block';

        const items = [
            ['Total Baris', result.total_rows],
            ['Berhasil Diimport', result.created],
            ['Dilewati (Duplikat)', result.skipped_duplicate],
            ['Dilewati (Filter)', result.skipped_filtered],
            ['Error', result.errors.length],
        ];

        resultSummary.innerHTML = items.map(([k, v]) => `
            <div class="review-item">
                <small>${k}</small>
                <strong>${v}</strong>
            </div>
        `).join('');

        if (result.errors.length) {
            resultErrors.innerHTML = `
                <h5 style="font-size:.78rem;font-weight:800;margin:1rem 0 .6rem;color:var(--su-danger)">
                    <i class="bi bi-exclamation-triangle"></i> Detail Error (${result.errors.length})
                </h5>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Baris</th><th>Alasan</th></tr></thead>
                        <tbody>
                            ${result.errors.slice(0, 100).map(e => `<tr><td>${e.row}</td><td>${e.reason}</td></tr>`).join('')}
                        </tbody>
                    </table>
                </div>
                ${result.errors.length > 100 ? `<p style="font-size:.72rem;color:var(--su-muted);margin-top:.5rem">Menampilkan 100 dari ${result.errors.length} error.</p>` : ''}
            `;
        } else {
            resultErrors.innerHTML = '';
        }

        Swal.fire({
            icon: 'success',
            title: 'Import selesai',
            text: `${result.created} case berhasil diimport, ${result.skipped_duplicate} duplikat dilewati.`,
        });
    }

})();