<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard'; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            /* Abu-abu netral agak terang */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* --- MEDIUM GLOWING SHAPES (Pas di Tengah) --- */
        .bg-shapes-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .glowing-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            /* Blur dilebarkan sedikit biar lebih nge-blend */
            animation: floatShape 18s infinite alternate ease-in-out;
        }

        .shape-blue {
            width: 480px;
            height: 480px;
            background: rgba(37, 99, 235, 0.45);
            /* Opacity 45% - Gak terlalu gonjreng */
            top: -10%;
            left: -5%;
        }

        .shape-teal {
            width: 420px;
            height: 420px;
            background: rgba(13, 148, 136, 0.40);
            /* Opacity 40% */
            bottom: -10%;
            right: -5%;
            animation-delay: -5s;
        }

        .shape-yellow {
            width: 350px;
            height: 350px;
            background: rgba(245, 158, 11, 0.30);
            /* Opacity 30% - Aksen tipis */
            top: 35%;
            left: 25%;
            animation-delay: -9s;
        }

        @keyframes floatShape {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(45px, -45px) scale(1.1);
            }
        }

        /* --- BALANCED GLASSMORPHISM CARDS --- */
        .soft-card {
            background: rgba(255, 255, 255, 0.92);
            /* 92% Putih - Tembus pandang tapi masih gampang dibaca */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
            /* Bayangan lebih soft */
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }

        .soft-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.98);
            /* Pas di-hover kartunya jadi lebih solid (jelas) */
        }

        /* --- GLASS NAVBAR --- */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            z-index: 1030;
        }

        /* --- SIDEBAR FLOATING GLASS --- */
        .sidebar-floating {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.03);
            min-height: calc(100vh - 110px);
        }

        .sidebar-link {
            color: #475569;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 12px;
            display: block;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background: rgba(59, 130, 246, 0.08);
            color: #2563eb;
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
            font-weight: 600;
            border-left: 4px solid #2563eb;
        }

        /* Tweak Tabel */
        .table-custom th {
            color: #64748b;
            font-weight: 600;
            border-bottom: 2px solid rgba(0, 0, 0, 0.04);
        }

        .table-custom td {
            vertical-align: middle;
            color: #1e293b;
            border-bottom: 1px solid rgba(0, 0, 0, 0.02);
        }
    </style>
</head>

<body>
    <div class="bg-shapes-container">
        <div class="glowing-shape shape-blue"></div>
        <div class="glowing-shape shape-teal"></div>
        <div class="glowing-shape shape-yellow"></div>
    </div>
    <?= $this->include('layouts/navbar'); ?>

    <div class="container-fluid">
        <div class="row">
            <?= $this->include('layouts/sidebar'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <?= $this->include('dashboard'); ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>