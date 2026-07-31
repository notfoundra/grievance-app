<?php

$current = service('uri')->getSegment(2);

?>

<aside class="sidebar" id="sidebar">

    <div class="brand">

        <div class="brand-logo">

            <img
                src="<?= base_url('assets/logo2.png') ?>"
                alt="PT Kahatex">

        </div>

        <div class="brand-text">

            <h3>PT KAHATEX</h3>

            <small>Grievance Management System</small>

        </div>

    </div>

    <nav class="sidebar-menu">

        <a href="<?= site_url('grievance') ?>"
            class="menu <?= ($current == '' || $current == null) ? 'active' : '' ?>">

            <i class="bi bi-speedometer2"></i>

            <span>Dashboard</span>

        </a>

        <a href="<?= site_url('grievance/case-log') ?>"
            class="menu <?= ($current == 'case-log') ? 'active' : '' ?>">

            <i class="bi bi-journal-text"></i>

            <span>Case Log</span>

        </a>

        <a href="<?= site_url('case/new') ?>"
            class="menu <?= ($current == 'new') ? 'active' : '' ?>">

            <i class="bi bi-plus-circle"></i>

            <span>New Case</span>

        </a>

        <a href="<?= site_url('grievance/follow-up') ?>"
            class="menu <?= ($current == 'follow-up') ? 'active' : '' ?>">

            <i class="bi bi-chat-left-text"></i>

            <span>Follow Up</span>

        </a>

        <a href="<?= site_url('grievance/reports') ?>"
            class="menu <?= ($current == 'reports') ? 'active' : '' ?>">

            <i class="bi bi-bar-chart"></i>

            <span>Reports</span>

        </a>

        <a href="<?= site_url('grievance/master-data') ?>"
            class="menu <?= ($current == 'master-data') ? 'active' : '' ?>">

            <i class="bi bi-database"></i>

            <span>Master Data</span>

        </a>

        <a href="<?= site_url('grievance/users') ?>"
            class="menu <?= ($current == 'users') ? 'active' : '' ?>">

            <i class="bi bi-people"></i>

            <span>Users</span>

        </a>

        <a href="<?= site_url('grievance/settings') ?>"
            class="menu <?= ($current == 'settings') ? 'active' : '' ?>">

            <i class="bi bi-gear"></i>

            <span>Settings</span>

        </a>

    </nav>

</aside>