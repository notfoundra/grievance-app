<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<?php $user = current_user(); ?>

<div class="page-header mb-4">
    <div>
        <h2>Follow Up Board</h2>
        <small>Monitor case progress based on its current status.</small>
    </div>
    <a href="<?= site_url('case/new') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Case
    </a>
</div>

<div class="filterbar card mb-4">

    <?php if ($user['role'] === 'admin') : ?>
        <div class="field">
            <label>Site</label>
            <select id="fbSite">
                <option value="">All Sites</option>
                <?php foreach ($sites as $s) : ?>
                    <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

    <div class="field">
        <label>Year</label>
        <select id="fbYear">
            <?php for ($y = (int) date('Y'); $y >= 2020; $y--) : ?>
                <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="field">
        <label>Department</label>
        <select id="fbDept">
            <option value="">All Departments</option>
            <?php foreach ($departments as $d) : ?>
                <option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Case Type</label>
        <select id="fbType">
            <option value="">All Case Types</option>
            <?php foreach ($caseTypes as $t) : ?>
                <option value="<?= $t['id'] ?>"><?= esc($t['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Priority</label>
        <select id="fbPriority">
            <option value="">All Priorities</option>
            <?php foreach ($priorities as $p) : ?>
                <option value="<?= $p['id'] ?>"><?= esc($p['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>&nbsp;</label>
        <div class="form-check" style="margin-top:.35rem">
            <input type="checkbox" id="fbIncludeClosed">
            <label for="fbIncludeClosed" style="font-size:.72rem;text-transform:none">Include Closed</label>
        </div>
    </div>

    <button class="btn btn-soft" id="fbReset">
        <i class="bi bi-arrow-counterclockwise"></i> Reset
    </button>

</div>

<div class="board" id="followBoard"></div>

<p class="board-note">Showing up to 500 most urgent cases based on current filter. Narrow the filter for more specific results.</p>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/follow_up.js') ?>"></script>
<?= $this->endSection() ?>