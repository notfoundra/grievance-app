<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header">

    <div>
        <a href="<?= site_url('grievance/case-log') ?>" class="btn btn-soft">
            <i class="bi bi-arrow-left"></i> Back to Case Log
        </a>
        <h2 style="margin-top:.6rem"><?= esc($case['case_number']) ?></h2>
        <small>Grievance Case Detail</small>
    </div>

    <div class="detail-action">
        <button type="button" class="btn btn-warning" id="btnOpenEdit">
            <i class="bi bi-pencil-square"></i> Edit Case
        </button>
        <button type="button" class="btn btn-primary" id="btnOpenFollowUp">
            <i class="bi bi-chat-left-text"></i> Follow Up
        </button>
    </div>

</div>

<div class="detail-grid">

    <!-- ========================= LEFT ========================= -->
    <div class="detail-left">

        <div class="detail-card card">
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

            <div class="text-center mb-3" style="text-align:center">
                <span class="status <?= $statusClass ?>" style="font-size:.75rem;padding:.5rem 1rem"><?= esc($case['status']) ?></span>
            </div>

            <div class="info-row"><small>Priority</small><strong><?= esc($case['priority'] ?? '-') ?></strong></div>
            <div class="info-row"><small>PIC</small><strong><?= esc($case['pic'] ?: '-') ?></strong></div>
            <div class="info-row"><small>Site</small><strong><?= esc($case['site'] ?? '-') ?></strong></div>
            <div class="info-row"><small>Department</small><strong><?= esc($case['department'] ?? '-') ?></strong></div>
            <div class="info-row"><small>Received</small><strong><?= esc($case['received_date']) ?></strong></div>
            <div class="info-row"><small>Target Close</small><strong><?= esc($case['target_closure_date']) ?></strong></div>
        </div>

        <div class="detail-card card">
            <h5>Timeline</h5>

            <div class="timeline">

                <div class="timeline-item">
                    <span class="timeline-dot dot-muted"><i class="bi bi-flag"></i></span>
                    <h6>Case Received</h6>
                    <p class="meta"><?= esc($case['received_date']) ?></p>
                </div>

                <?php foreach ($updates as $u) : ?>
                    <div class="timeline-item">
                        <span class="timeline-dot"><i class="bi bi-check2"></i></span>
                        <h6>
                            Status changed to
                            <span class="status <?= match ($u['status_name']) {
                                                    'Open' => 'status-open',
                                                    'In Progress' => 'status-progress',
                                                    'Closed' => 'status-closed',
                                                    'Overdue' => 'status-overdue',
                                                    default => 'status-open'
                                                } ?>" style="font-size:.62rem"><?= esc($u['status_name']) ?></span>
                        </h6>
                        <p><?= esc($u['note']) ?></p>

                        <?php if (! empty($u['attachments'])) : ?>
                            <div class="timeline-attachments">
                                <?php foreach ($u['attachments'] as $att) : ?>
                                    <a href="<?= site_url('case/attachment/' . $att['id']) ?>">
                                        <i class="bi bi-paperclip"></i> <?= esc($att['original_name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <p class="meta">
                            <?= esc($u['updated_by'] ?: 'System') ?> ·
                            <?= esc(date('d M Y, H:i', strtotime($u['created_at']))) ?>
                        </p>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($updates)) : ?>
                    <div class="timeline-item">
                        <span class="timeline-dot dot-muted"><i class="bi bi-hourglass"></i></span>
                        <h6>No follow up yet</h6>
                        <p class="meta">Click "Follow Up" to record the first update.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

    <!-- ========================= RIGHT ========================= -->
    <div class="detail-right">

        <div class="section-card">
            <div class="section-title"><i class="bi bi-info-circle me-2"></i> Case Information</div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item"><label>Site</label>
                        <div><?= esc($case['site'] ?? '-') ?></div>
                    </div>
                    <div class="info-item"><label>Department</label>
                        <div><?= esc($case['department'] ?? '-') ?></div>
                    </div>
                    <div class="info-item"><label>Channel</label>
                        <div><?= esc($case['channel'] ?? '-') ?></div>
                    </div>
                    <div class="info-item"><label>Message Type</label>
                        <div><?= esc($case['message_type'] ?? '-') ?></div>
                    </div>
                    <div class="info-item"><label>Case Type</label>
                        <div><?= esc($case['case_type'] ?? '-') ?></div>
                    </div>
                    <div class="info-item"><label>Confidential</label>
                        <div><?= esc($case['confidential']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title"><i class="bi bi-chat-left-text me-2"></i> Grievance Message</div>
            <div class="section-body"><?= nl2br(esc($case['message'])) ?></div>
        </div>

        <div class="section-card">
            <div class="section-title"><i class="bi bi-reply me-2"></i> Management Response</div>
            <div class="section-body">
                <?= $case['management_response'] ? nl2br(esc($case['management_response'])) : '<span style="color:var(--su-muted)">No response yet.</span>' ?>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title"><i class="bi bi-diagram-3 me-2"></i> Root Cause Analysis</div>
            <div class="section-body">
                <?= $case['root_cause'] ? nl2br(esc($case['root_cause'])) : '<span style="color:var(--su-muted)">Not available.</span>' ?>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title"><i class="bi bi-check2-square me-2"></i> Corrective Action</div>
            <div class="section-body">
                <?= $case['corrective_action'] ? nl2br(esc($case['corrective_action'])) : '<span style="color:var(--su-muted)">Not available.</span>' ?>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title"><i class="bi bi-paperclip me-2"></i> Evidence Attachments</div>
            <div class="section-body">
                <?php if (empty($caseAttachments)) : ?>
                    <span style="color:var(--su-muted)">No attachments.</span>
                <?php else : ?>
                    <?php foreach ($caseAttachments as $att) : ?>
                        <div class="attachment-chip">
                            <i class="bi bi-file-earmark"></i>
                            <a href="<?= site_url('case/attachment/' . $att['id']) ?>"><?= esc($att['original_name']) ?></a>
                            <span class="size"><?= round($att['file_size'] / 1024) ?> KB</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="section-card">
            <div class="section-title"><i class="bi bi-star me-2"></i> Satisfaction</div>
            <div class="section-body" style="text-align:center">
                <h2 style="margin:.2rem 0"><?= str_repeat('⭐', (int) ($case['rating'] ?: 0)) ?: '-' ?></h2>
                <div style="color:var(--su-muted)"><?= esc($case['satisfaction'] ?: '-') ?></div>
            </div>
        </div>

    </div>

</div>

<!-- ===================== MODAL: EDIT CASE ===================== -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box">

        <div class="modal-header">
            <h4><i class="bi bi-pencil-square"></i> Edit Case — <?= esc($case['case_number']) ?></h4>
            <button type="button" class="modal-close" data-close="modalEdit"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="formEdit">

            <div class="modal-body">

                <div class="form-grid two mb-3">

                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id" required>
                            <?php foreach ($departments as $d) : ?>
                                <option value="<?= $d['id'] ?>" <?= $d['id'] == $case['department_id'] ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Priority</label>
                        <select name="priority_id" required>
                            <?php foreach ($priorities as $p) : ?>
                                <option value="<?= $p['id'] ?>" <?= $p['id'] == $case['priority_id'] ? 'selected' : '' ?>><?= esc($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>PIC</label>
                        <input type="text" name="pic" value="<?= esc($case['pic'] ?? '') ?>" placeholder="Nama penanggung jawab">
                    </div>

                    <div class="form-group">
                        <label>Target Response Date</label>
                        <input type="date" name="target_response_date" value="<?= esc($case['target_response_date']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Target Closure Date</label>
                        <input type="date" name="target_closure_date" value="<?= esc($case['target_closure_date']) ?>" required>
                    </div>

                </div>

                <div class="form-group mb-3">
                    <label>Management Response</label>
                    <textarea name="management_response" rows="3"><?= esc($case['management_response'] ?? '') ?></textarea>
                </div>

                <div class="form-grid two mb-3">
                    <div class="form-group">
                        <label>Root Cause</label>
                        <textarea name="root_cause" rows="4"><?= esc($case['root_cause'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Corrective Action</label>
                        <textarea name="corrective_action" rows="4"><?= esc($case['corrective_action'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-grid two mb-3">
                    <div class="form-group">
                        <label>Confidential</label>
                        <div class="form-check">
                            <input type="checkbox" name="confidential" value="1" id="editConfidential" <?= $case['confidential'] === 'Yes' ? 'checked' : '' ?>>
                            <label for="editConfidential">Hide complainant identity</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Repeated Case</label>
                        <div class="form-check">
                            <input type="checkbox" name="repeated_case" value="1" id="editRepeat" <?= $case['repeated_case'] === 'Yes' ? 'checked' : '' ?>>
                            <label for="editRepeat">This issue happened before</label>
                        </div>
                    </div>
                </div>

                <div class="form-grid two">
                    <div class="form-group">
                        <label>Satisfaction Rating</label>
                        <div class="rating-input" id="ratingInput">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <i class="bi bi-star<?= $i <= (int) ($case['rating'] ?? 0) ? '-fill active' : '' ?>" data-value="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="<?= esc($case['rating'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Satisfaction Label</label>
                        <select name="satisfaction">
                            <option value="">-</option>
                            <option value="Satisfied" <?= $case['satisfaction'] === 'Satisfied' ? 'selected' : '' ?>>Satisfied</option>
                            <option value="Unsatisfied" <?= $case['satisfaction'] === 'Unsatisfied' ? 'selected' : '' ?>>Unsatisfied</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-soft" data-close="modalEdit">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save Changes</button>
            </div>

        </form>

    </div>
</div>

<!-- ===================== MODAL: FOLLOW UP ===================== -->
<div class="modal-overlay" id="modalFollowUp">
    <div class="modal-box modal-sm">

        <div class="modal-header">
            <h4><i class="bi bi-chat-left-text"></i> Add Follow Up</h4>
            <button type="button" class="modal-close" data-close="modalFollowUp"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="formFollowUp" enctype="multipart/form-data">

            <div class="modal-body">

                <div class="form-group mb-3">
                    <label>New Status</label>
                    <select name="status_id" required>
                        <?php foreach ($statuses as $s) : ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $case['status_id'] ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Note <span style="color:var(--su-danger)">*</span></label>
                    <textarea name="note" rows="4" placeholder="Jelaskan perkembangan/tindak lanjut kasus ini..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Attach Evidence (optional)</label>
                    <input type="file" name="attachment[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-soft" data-close="modalFollowUp">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Submit Follow Up</button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    const CASE_ID = <?= (int) $case['id'] ?>;
</script>
<script src="<?= base_url('assets/grievance/js/case_detail.js') ?>"></script>
<?= $this->endSection() ?>