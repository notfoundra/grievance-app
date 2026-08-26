<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<?php $user = current_user(); ?>

<div class="page-header mb-4">
    <div>
        <h2>Create New Case</h2>
        <small>Submit a new grievance case.</small>
    </div>
    <a href="<?= site_url('grievance/case-log') ?>" class="btn btn-soft">
        <i class="bi bi-arrow-left"></i> Back
    </a>
    <?php if (has_role('admin')) : ?>
        <a href="<?= site_url('grievance/import') ?>" class="btn btn-soft">
            <i class="bi bi-file-earmark-excel"></i> Import WOVO
        </a>
    <?php endif; ?>
</div>

<!-- Wizard tabs -->
<div class="wizard-tabs" id="wizardTabs">
    <div class="wizard-tab active" data-step="1">
        <span class="num">1</span>
        <span class="label">Information</span>
    </div>
    <div class="wizard-tab" data-step="2">
        <span class="num">2</span>
        <span class="label">Complaint</span>
    </div>
    <div class="wizard-tab" data-step="3">
        <span class="num">3</span>
        <span class="label">Attachment</span>
    </div>
    <div class="wizard-tab" data-step="4">
        <span class="num">4</span>
        <span class="label">Review</span>
    </div>
</div>

<div class="alert alert-primary mb-4">
    <div>
        <strong>Case Number</strong><br>
        <span id="previewCaseNo">Generated automatically upon submission</span>
    </div>
    <span class="status status-open">OPEN</span>
</div>

<form id="formCase" class="card form-card" enctype="multipart/form-data" novalidate>

    <?= csrf_field() ?>

    <!-- ===================== STEP 1 — INFORMATION ===================== -->
    <div class="tab-pane active" data-pane="1">

        <div class="form-grid mb-3">

            <?php if ($user['role'] === 'admin') : ?>
                <div class="form-group">
                    <label>Site <span style="color:var(--su-danger)">*</span></label>
                    <select name="site_id" required>
                        <option value="">Select Site</option>
                        <?php foreach ($sites as $row) : ?>
                            <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                    <span class="error-text">Site is required.</span>
                </div>
            <?php else : ?>
                <div class="form-group">
                    <label>Site</label>
                    <input type="text" value="<?= esc($sites[0]['name'] ?? '-') ?>" disabled>
                    <input type="hidden" name="site_id" value="<?= esc($sites[0]['id'] ?? '') ?>">
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Department</label>
                <select name="department_id">
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $row) : ?>
                        <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label>Channel</label>
                <select name="channel_id">
                    <option value="">Select Channel</option>
                    <?php foreach ($channels as $row) : ?>
                        <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label>Message Type</label>
                <select name="message_type_id">
                    <option value="">Select Type</option>
                    <?php foreach ($messageTypes as $row) : ?>
                        <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender">
                    <option value="">Select Type</option>
                    <option value="male">male</option>
                    <option value="female">female</option>
                </select>
            </div>

            <div class="form-group">
                <label>Case Type</label>
                <select name="case_type_id">
                    <option value="">Select Case Type</option>
                    <?php foreach ($caseTypes as $row) : ?>
                        <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label>Priority</label>
                <select name="priority_id">
                    <option value="">Select Priority</option>
                    <?php foreach ($priorities as $row) : ?>
                        <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

        </div>

        <div class="wizard-footer">
            <div class="spacer"></div>
            <button type="button" class="btn btn-primary btn-next" data-next="2">
                Next <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>

    <!-- ===================== STEP 2 — COMPLAINT ===================== -->
    <div class="tab-pane" data-pane="2">

        <div class="form-group mb-3">
            <label>Complaint Message <span style="color:var(--su-danger)">*</span></label>
            <textarea name="message" rows="8" placeholder="Write grievance detail here (minimum 10 characters)..." required></textarea>
            <span class="error-text">Message must be at least 10 characters.</span>
        </div>

        <div class="form-grid form-grid-3 mb-3">

            <div class="form-group">
                <label>Received Date</label>
                <input type="date" value="<?= date('Y-m-d') ?>">
                <span class="hint">Set automatically to today.</span>
            </div>

            <div class="form-group">
                <label>Target Response Date <span style="color:var(--su-danger)">*</span></label>
                <input type="date" id="targetResponse" name="target_response_date" required>
                <span class="error-text">Target response date is required.</span>
            </div>

            <div class="form-group">
                <label>Target Closure Date <span style="color:var(--su-danger)">*</span></label>
                <input type="date" id="targetClosure" name="target_closure_date" required>
                <span class="error-text">Target closure date is required and must not be earlier than response date.</span>
            </div>

        </div>

        <div class="form-grid two">

            <div class="form-group">
                <label>Confidential</label>
                <div class="form-check">
                    <input type="checkbox" name="confidential" value="1" id="confidential">
                    <label for="confidential">Hide complainant identity</label>
                </div>
            </div>

            <div class="form-group">
                <label>Repeated Case</label>
                <div class="form-check">
                    <input type="checkbox" name="repeated_case" value="1" id="repeat">
                    <label for="repeat">This issue happened before</label>
                </div>
            </div>

        </div>

        <div class="wizard-footer">
            <button type="button" class="btn btn-soft btn-prev" data-prev="1">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <div class="spacer"></div>
            <button type="button" class="btn btn-primary btn-next" data-next="3">
                Next <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>

    <!-- ===================== STEP 3 — ATTACHMENT ===================== -->
    <div class="tab-pane" data-pane="3">

        <label class="upload-box" id="dropArea">
            <i class="bi bi-cloud-arrow-up"></i>
            <strong>Drag &amp; Drop files here, or click to browse</strong>
            <span>JPG, PNG, PDF, DOC, DOCX — Max 5 MB per file</span>
            <input id="attachment" type="file" name="attachment[]" multiple hidden accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        </label>

        <div id="previewFiles" class="mt-3"></div>

        <div class="wizard-footer">
            <button type="button" class="btn btn-soft btn-prev" data-prev="2">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <div class="spacer"></div>
            <button type="button" class="btn btn-primary btn-next" data-next="4">
                Next <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>

    <!-- ===================== STEP 4 — REVIEW ===================== -->
    <div class="tab-pane" data-pane="4">

        <div class="review-block">
            <h5><i class="bi bi-info-circle"></i> General Information</h5>
            <div class="review-grid" id="reviewInfo"></div>
        </div>

        <div class="review-block">
            <h5><i class="bi bi-chat-left-text"></i> Complaint Message</h5>
            <div class="review-message" id="reviewMessage">-</div>
        </div>

        <div class="review-block">
            <h5><i class="bi bi-paperclip"></i> Attachments</h5>
            <div id="reviewAttachments" class="review-message">No files attached.</div>
        </div>

        <div class="alert alert-primary mb-3">
            <div>
                <strong>Please review before submitting.</strong><br>
                <span style="font-size:.72rem">Case number will be generated automatically, status will be set to Open.</span>
            </div>
        </div>

        <div class="wizard-footer">
            <button type="button" class="btn btn-soft btn-prev" data-prev="3">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <div class="spacer"></div>
            <button type="submit" id="btnSubmit" class="btn btn-success">
                <i class="bi bi-send"></i> Submit Case
            </button>
        </div>

    </div>

</form>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/new_case.js') ?>"></script>
<?= $this->endSection() ?>