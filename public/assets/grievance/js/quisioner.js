(function () {

    if (window.__quisionerJsLoaded) return;
    window.__quisionerJsLoaded = true;

    const select = document.getElementById('quizSelect');

    if (! select || ! select.value) return; // gak ada data master quisioner

    let charts = { pass: null, score: null };

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

    function renderScoreChart(participants) {
        const canvas = document.getElementById('quizScoreChart');
        destroyIfExists(canvas);

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: participants.map(p => p.name),
                datasets: [
                    {
                        label: 'Pretest',
                        data: participants.map(p => Number(p.pretest)),
                        backgroundColor: '#11cdef',
                        borderRadius: 5,
                    },
                    {
                        label: 'Posttest',
                        data: participants.map(p => Number(p.posttest)),
                        backgroundColor: '#5e72e4',
                        borderRadius: 5,
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { precision: 0 }, grid: { color: '#f0f2f7' } },
                    x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                }
            }
        });
    }

    function renderTable(participants, passingScore) {
        const body = document.getElementById('quizTableBody');
        document.getElementById('quizTableCount').textContent = `${participants.length} peserta`;

        if (! participants.length) {
            body.innerHTML = `<tr><td colspan="6" class="empty-state">Belum ada peserta untuk quisioner ini.</td></tr>`;
            return;
        }

        body.innerHTML = participants.map(p => {
            const pre = Number(p.pretest);
            const post = Number(p.posttest);
            const delta = post - pre;
            const lulus = post >= passingScore;

            return `
                <tr>
                    <td><strong>${p.name}</strong></td>
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

    function loadData(masterId) {
        const batchLabel = select.options[select.selectedIndex].text;
        document.getElementById('quizBatchLabel').textContent = batchLabel;

        fetch(`${APP.baseUrl}grievance/quisioner/data/${masterId}`)
            .then(res => res.json())
            .then(data => {
                renderPassChart(data.summary);
                renderScoreChart(data.participants);
                renderTable(data.participants, data.passing_score);

                document.getElementById('quizAvgLabel').textContent =
                    `Rata-rata Pretest: ${data.summary.avg_pretest} · Posttest: ${data.summary.avg_posttest}`;
            })
            .catch(() => {
                document.getElementById('quizTableBody').innerHTML =
                    `<tr><td colspan="6" class="empty-state">Gagal memuat data.</td></tr>`;
            });
    }

    select.addEventListener('change', () => loadData(select.value));

    loadData(select.value);

})();