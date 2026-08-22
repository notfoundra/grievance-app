<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header mb-4">
    <div>
        <h2>Create New Case</h2>
        <small>Submit a new grievance case.</small>
    </div>
    <a href="<?= site_url('case/case-log') ?>" class="btn btn-soft">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<!-- Wizard -->
<div class="wizard mb-4">
    <div class="wizard-step active"><span>1</span> Information</div>
    <div class="wizard-step"><span>2</span> Complaint</div>
    <div class="wizard-step"><span>3</span> Attachment</div>
    <div class="wizard-step"><span>4</span> Review</div>
</div>

<!-- Case number preview -->
<div class="alert alert-primary d-flex justify-content-between align-items-center mb-4">
    <div>
        <strong>Case Number</strong><br>
        <span id="previewCaseNo">Generating...</span>
    </div>
    <div>
        <small>Status :</small>
        <span class="badge badge-open">OPEN</span>
    </div>
</div>

<form id="formCase" enctype="multipart/form-data">

    <!-- ======================= GENERAL INFORMATION ======================= -->
    <div class="section-card mb-4">
        <div class="section-title">
            <i class="bi bi-info-circle me-2"></i> General Information
        </div>
        <div class="section-body">
            <div class="form-grid">

                <div class="form-group">
                    <label>Site <span class="text-danger">*</span></label>
                    <select name="site_id" required>
                        <option value="">Select Site</option>
                        <?php foreach ($sites as $row): ?>
                            <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id">
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $row): ?>
                            <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Channel</label>
                    <select name="channel_id">
                        <option value="">Select Channel</option>
                        <?php foreach ($channels as $row): ?>
                            <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Message Type</label>
                    <select name="message_type_id">
                        <option value="">Select Type</option>
                        <?php foreach ($messageTypes as $row): ?>
                            <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Case Type</label>
                    <select name="case_type_id">
                        <option value="">Select Case Type</option>
                        <?php foreach ($caseTypes as $row): ?>
                            <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority_id">
                        <option value="">Select Priority</option>
                        <?php foreach ($priorities as $row): ?>
                            <option value="<?= $row['id'] ?>"><?= esc($row['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    <!-- ======================= COMPLAINT DETAIL ======================= -->
    <div class="section-card mb-4">
        <div class="section-title">
            <i class="bi bi-chat-left-text me-2"></i> Complaint Detail
        </div>
        <div class="section-body">

            <div class="form-group mb-3">
                <label>Complaint Message</label>
                <textarea rows="8" name="message" placeholder="Write grievance detail here..." required></textarea>
            </div>

            <div class="form-grid form-grid-3 mb-3">
                <div class="form-group">
                    <label>Received Date</label>
                    <input type="date" value="<?= date('Y-m-d') ?>" disabled>
                    <small class="text-muted">Set automatically to today, not editable.</small>
                </div>
                <div class="form-group">
                    <label>Target Response Date <span class="text-danger">*</span></label>
                    <input type="date" id="targetResponse" name="target_response_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Target Closure Date <span class="text-danger">*</span></label>
                    <input type="date" id="targetClosure" name="target_closure_date" class="form-control" required>
                </div>

            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Confidential</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="confidential" value="1" id="confidential">
                        <label class="form-check-label" for="confidential">Hide complainant identity</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Repeated Case</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="repeated_case" value="1" id="repeat">
                        <label class="form-check-label" for="repeat">This issue happened before</label>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- ======================= EVIDENCE ATTACHMENT ======================= -->
    <div class="section-card mb-4">
        <div class="section-title">
            <i class="bi bi-paperclip me-2"></i> Evidence Attachment
        </div>
        <div class="section-body">

            <label class="upload-box" id="dropArea">
                <i class="bi bi-cloud-arrow-up fs-1"></i>
                <strong>Drag & Drop files here</strong>
                <span>JPG, PNG, PDF, DOCX (Max 5 MB)</span>
                <input id="attachment" type="file" name="attachment[]" multiple hidden>
            </label>

            <div id="previewFiles" class="mt-3"></div>

        </div>
    </div>

    <!-- ======================= REVIEW ======================= -->
    <div class="section-card mb-4">
        <div class="section-title">
            <i class="bi bi-card-checklist me-2"></i> Review
        </div>
        <div class="section-body">
            <div class="alert alert-info mb-0">
                <strong>Please review before submitting.</strong>
                <ul class="mb-0 mt-2">
                    <li>Case Number will be generated automatically.</li>
                    <li>Status will be <strong>Open</strong>.</li>
                    <li>Target Response &amp; Closure dates are pre-filled as a suggestion (+3 / +14 days) but can be adjusted manually.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ======================= FOOTER ======================= -->
    <div class="form-footer">
        <button type="reset" class="btn btn-soft">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
        </button>

        <div class="detail-action">
            <button type="button" id="btnDraft" class="btn btn-warning">
                <i class="bi bi-floppy"></i> Save Draft
            </button>
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