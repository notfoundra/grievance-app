<aside class="sidebar" id="sidebar">
    <div class="brand">
        <!-- Logonya diganti pakai image tag -->
        <img src="<?= base_url('assets/logo2.png'); ?>" alt="Logo Kahatex" style="width: 45px; height: auto; object-fit: contain;">

        <div>
            <h1>PT KAHATEX</h1>
            <p>GRIEVANCE MECHANISM</p>
        </div>
    </div>

    <nav class="nav" id="nav">
        <button class="nav-btn active" data-page="dashboard"><span class="nav-ico">⌂</span><span>Dashboard</span></button>
        <button class="nav-btn" data-page="case-log"><span class="nav-ico">▤</span><span>Case Log</span></button>
        <button class="nav-btn" data-page="new-case"><span class="nav-ico">⊕</span><span>New Case</span></button>
        <button class="nav-btn" data-page="follow-up"><span class="nav-ico">↻</span><span>Follow Up</span></button>
        <button class="nav-btn" data-page="reports"><span class="nav-ico">⌁</span><span>Reports</span></button>
        <button class="nav-btn" data-page="master-data"><span class="nav-ico">⌘</span><span>Master Data</span></button>
        <button class="nav-btn" data-page="users"><span class="nav-ico">♙</span><span>Users</span></button>
        <button class="nav-btn" data-page="settings"><span class="nav-ico">⚙</span><span>Settings</span></button>
    </nav>

    <div class="sidebar-bottom">
        <div class="site-card"><small>Current Site</small><strong>All Sites</strong></div>
        <button class="collapse" id="closeSidebar">‹‹ &nbsp; Collapse</button>
    </div>
</aside>