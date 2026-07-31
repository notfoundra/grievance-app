<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<section
    id="page-dashboard"
    class="page active">
    <?= view('grievance/dashboard') ?>


</section>


<section
    id="page-case-detail"
    class="page">

    Case Detail

</section>

<section
    id="page-follow-up"
    class="page">

    Follow Up

</section>

<section
    id="page-reports"
    class="page">

    Reports

</section>

<section
    id="page-master-data"
    class="page">

    Master Data

</section>

<section
    id="page-users"
    class="page">

    Users

</section>

<section
    id="page-settings"
    class="page">

    Settings

</section>

<?= $this->endSection() ?>