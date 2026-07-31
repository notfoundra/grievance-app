<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="soft-card card-accent-blue p-4">
            <p class="text-muted mb-1 small fw-bold text-uppercase tracking-wide">TOTAL KASUS MASUK</p>
            <h2 class="fw-bolder mb-0" style="color: #1e293b;"><?= $total_kasus; ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="soft-card card-accent-yellow p-4">
            <p class="text-muted mb-1 small fw-bold text-uppercase tracking-wide">DALAM PENANGANAN</p>
            <h2 class="fw-bolder mb-0 text-warning"><?= $in_progress; ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="soft-card card-accent-green p-4">
            <p class="text-muted mb-1 small fw-bold text-uppercase tracking-wide">SELESAI (RESOLVED)</p>
            <h2 class="fw-bolder mb-0 text-success"><?= $resolved; ?></h2>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Daftar Kasus (Table) -->
    <div class="col-lg-7">
        <div class="soft-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0">Daftar Kasus Aktif</h5>
                <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">Lihat Semua</span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID Kasus</th>
                            <th>Kategori</th>
                            <th>Tenggat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cases as $row): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?= $row['no_kasus']; ?></td>
                                <td class="fw-medium text-secondary"><?= $row['kategori']; ?></td>
                                <td><?= $row['due_date']; ?></td>
                                <td><span class="badge <?= $row['badge']; ?> px-3 py-2 rounded-pill shadow-sm"><?= $row['status']; ?></span></td>
                                <td><button class="btn btn-light border btn-sm text-primary fw-semibold shadow-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalDetail">Detail</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Area Chart Interaktif -->
    <div class="col-lg-5">
        <!-- Grafik Bar Trend -->
        <div class="soft-card p-4 mb-4">
            <h6 class="fw-bold text-dark mb-1">Tren Aduan (6 Bulan Terakhir)</h6>
            <p class="text-muted small mb-4">Frekuensi kasus masuk vs diselesaikan</p>

            <!-- PERBAIKAN: Wrapper pembatas Chart biar gak jebol ke bawah -->
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="trendChart"></canvas>
            </div>

        </div>
    </div>
</div>

<!-- Tambahkan script Chart.js via CDN di bagian bawah file ini -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Chart.js pas halaman diload
        const ctx = document.getElementById('trendChart').getContext('2d');

        // Bikin gradient buat grafiknya biar mewah
        let gradientBlue = ctx.createLinearGradient(0, 0, 0, 400);
        gradientBlue.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradientBlue.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                datasets: [{
                        label: 'Laporan Masuk',
                        data: [12, 19, 15, 25, 22, 30],
                        backgroundColor: gradientBlue,
                        borderRadius: 6, // Biar ujung grafiknya tumpul (modern)
                        borderWidth: 0
                    },
                    {
                        label: 'Diselesaikan',
                        data: [10, 17, 14, 20, 21, 28],
                        backgroundColor: '#cbd5e1',
                        borderRadius: 6,
                        borderWidth: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Udah aman karena udah dibungkus div relative
                animation: {
                    y: {
                        duration: 2000, // Durasi naik 2 detik
                        easing: 'easeOutQuart'
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: {
                                family: 'Segoe UI'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5],
                            color: '#e2e8f0'
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>