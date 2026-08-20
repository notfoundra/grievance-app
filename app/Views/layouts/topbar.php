<header class="topbar">

    <div class="top-left">
        <button id="btnSidebar" class="icon-btn d-md-none">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h2 id="pageTitle"><?= $title ?? 'Dashboard' ?></h2>
            <small>Home / <span id="breadcrumb"><?= $title ?? 'Dashboard' ?></span></small>
        </div>
    </div>

    <div class="top-right">

        <button class="icon-btn">
            <i class="bi bi-bell"></i>
            <span class="notif-dot">3</span>
        </button>

        <div class="profile">
            <div class="avatar"><?= esc(strtoupper(substr(current_user()['name'] ?? 'U', 0, 1))) ?></div>
            <div>
                <strong><?= esc(current_user()['name'] ?? '') ?></strong>
                <small><?= esc(ucfirst(current_user()['role'] ?? '')) ?></small>
            </div>
            <a href="<?= site_url('logout') ?>" class="icon-btn" title="Logout" style="margin-left:.4rem">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>

    </div>

</header>