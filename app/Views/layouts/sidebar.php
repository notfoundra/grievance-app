<?php

$path     = trim(service('request')->getUri()->getPath(), '/');
$segments = $path === '' ? [] : explode('/', $path);
$current  = $segments[1] ?? '';

$user = current_user();

$menus = [
    ['segment' => '',          'url' => 'grievance',            'icon' => 'bi-speedometer2',   'label' => 'Dashboard',   'roles' => ['admin', 'socks', 'garmen']],
    ['segment' => 'case-log',  'url' => 'grievance/case-log',   'icon' => 'bi-journal-text',   'label' => 'Case Log',    'roles' => ['admin', 'socks', 'garmen']],
    ['segment' => 'new',       'url' => 'case/new',             'icon' => 'bi-plus-circle',    'label' => 'New Case',    'roles' => ['admin', 'socks', 'garmen']],
    ['segment' => 'follow-up', 'url' => 'grievance/follow-up',  'icon' => 'bi-chat-left-text', 'label' => 'Follow Up',   'roles' => ['admin', 'socks', 'garmen']],
    ['segment' => 'reports',   'url' => 'grievance/reports',    'icon' => 'bi-bar-chart',      'label' => 'Reports',     'roles' => ['admin']],
    ['segment' => 'master-data', 'url' => 'grievance/master-data', 'icon' => 'bi-database',    'label' => 'Master Data', 'roles' => ['admin']],
    ['segment' => 'user',     'url' => 'user',      'icon' => 'bi-people',         'label' => 'User',       'roles' => ['admin']],
    ['segment' => 'settings',  'url' => 'grievance/settings',   'icon' => 'bi-gear',           'label' => 'Settings',    'roles' => ['admin']],
];

?>

<aside class="sidebar" id="sidebar">

    <div class="brand">
        <img src="<?= base_url('assets/logo1.png') ?>" alt="PT Kahatex">
        <div class="brand-text">
            <h3>PT KAHATEX</h3>
            <small>Grievance Management</small>
        </div>
    </div>

    <nav class="sidebar-menu">

        <?php foreach ($menus as $item) : ?>

            <?php if (! in_array($user['role'] ?? '', $item['roles'], true)) continue; ?>

            <a href="<?= site_url($item['url']) ?>"
                class="menu <?= $current == $item['segment'] ? 'active' : '' ?>">
                <i class="bi <?= $item['icon'] ?>"></i>
                <span><?= esc($item['label']) ?></span>
            </a>

        <?php endforeach; ?>

    </nav>

</aside>