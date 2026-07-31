<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header">

    <div>

        <a href="<?= site_url('case') ?>" class="btn btn-soft">

            <i class="bi bi-arrow-left"></i>

            Back to Case Log

        </a>

        <h2>

            <?= esc($case['case_number']) ?>

        </h2>

        <small>

            Grievance Case Detail

        </small>

    </div>

    <div class="detail-action">

        <a href="#" class="btn btn-warning">

            <i class="bi bi-pencil-square"></i>

            Edit Case

        </a>

        <a href="#" class="btn btn-primary">

            <i class="bi bi-chat-left-text"></i>

            Follow Up

        </a>

    </div>

</div>

<div class="detail-wrapper">

    <!-- ========================================= -->
    <!-- LEFT -->
    <!-- ========================================= -->

    <div class="detail-left">

        <div class="detail-card">

            <h5>Case Summary</h5>

            <?php

            $statusClass = match ($case['status']) {
                'Open' => 'status-open',
                'In Progress' => 'status-progress',
                'Closed' => 'status-closed',
                'Overdue' => 'status-overdue',
                default => 'status-open'
            };

            ?>

            <div class="text-center mb-4">

                <span class="status-pill <?= $statusClass ?>">

                    <?= esc($case['status']) ?>

                </span>

            </div>

            <div class="info-row">

                <small>Priority</small>

                <strong><?= esc($case['priority']) ?></strong>

            </div>

            <div class="info-row">

                <small>PIC</small>

                <strong><?= esc($case['pic']) ?></strong>

            </div>

            <div class="info-row">

                <small>Site</small>

                <strong><?= esc($case['site']) ?></strong>

            </div>

            <div class="info-row">

                <small>Department</small>

                <strong><?= esc($case['department']) ?></strong>

            </div>

            <div class="info-row">

                <small>Received</small>

                <strong><?= esc($case['received_date']) ?></strong>

            </div>

            <div class="info-row">

                <small>Target Close</small>

                <strong><?= esc($case['target_closure_date']) ?></strong>

            </div>

        </div>

        <div class="detail-card">

            <h5>Timeline</h5>

            <ul class="timeline">

                <li>

                    <span>Case Received</span>

                    <small><?= esc($case['received_date']) ?></small>

                </li>

                <li>

                    <span>Management Response</span>

                    <small><?= $case['response_date'] ?: '-' ?></small>

                </li>

                <li>

                    <span>Case Closed</span>

                    <small><?= $case['closed_date'] ?: '-' ?></small>

                </li>

            </ul>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- RIGHT -->
    <!-- ========================================= -->

    <div class="detail-right">

        <div class="section-card">

            <div class="section-title">

                <i class="bi bi-info-circle me-2"></i>

                Case Information

            </div>

            <div class="section-body">

                <div class="info-grid">

                    <div class="info-item">

                        <label>Site</label>

                        <div><?= esc($case['site']) ?></div>

                    </div>

                    <div class="info-item">

                        <label>Department</label>

                        <div><?= esc($case['department']) ?></div>

                    </div>

                    <div class="info-item">

                        <label>Channel</label>

                        <div><?= esc($case['channel']) ?></div>

                    </div>

                    <div class="info-item">

                        <label>Message Type</label>

                        <div><?= esc($case['message_type']) ?></div>

                    </div>

                    <div class="info-item">

                        <label>Case Type</label>

                        <div><?= esc($case['case_type']) ?></div>

                    </div>

                    <div class="info-item">

                        <label>Confidential</label>

                        <div><?= esc($case['confidential']) ?></div>

                    </div>

                </div>

            </div>

        </div>

        <div class="section-card">

            <div class="section-title">

                <i class="bi bi-chat-left-text me-2"></i>

                Grievance Message

            </div>

            <div class="section-body">

                <?= nl2br(esc($case['message'])) ?>

            </div>

        </div>

        <div class="section-card">

            <div class="section-title">

                <i class="bi bi-reply me-2"></i>

                Management Response

            </div>

            <div class="section-body">

                <?= $case['management_response'] ? nl2br(esc($case['management_response'])) : '<span class="text-muted">No response yet.</span>' ?>

            </div>

        </div>

        <div class="section-card">

            <div class="section-title">

                <i class="bi bi-diagram-3 me-2"></i>

                Root Cause Analysis

            </div>

            <div class="section-body">

                <?= $case['root_cause'] ? nl2br(esc($case['root_cause'])) : '<span class="text-muted">Not available.</span>' ?>

            </div>

        </div>

        <div class="section-card">

            <div class="section-title">

                <i class="bi bi-check2-square me-2"></i>

                Corrective Action

            </div>

            <div class="section-body">

                <?= $case['corrective_action'] ? nl2br(esc($case['corrective_action'])) : '<span class="text-muted">Not available.</span>' ?>

            </div>

        </div>

        <div class="section-card">

            <div class="section-title">

                <i class="bi bi-star me-2"></i>

                Satisfaction

            </div>

            <div class="section-body text-center">

                <h2 class="mb-2">

                    ⭐ <?= esc($case['rating'] ?: '-') ?>/5

                </h2>

                <div class="text-muted">

                    <?= esc($case['satisfaction'] ?: '-') ?>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>