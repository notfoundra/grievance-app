const NewCase = {

    MAX_FILE_SIZE: 5 * 1024 * 1024, // 5 MB
    ALLOWED_EXT: ["jpg", "jpeg", "png", "pdf", "doc", "docx"],

    init() {
        this.prepare();
        this.bind();
    },

    /* ==========================================================
       Preview values. Target Response/Closure are pre-filled with a
       suggested date (+3 / +14 days) but the fields stay editable —
       the user enters/adjusts them manually before submitting.
       ========================================================== */
    prepare() {
        const now = new Date();
        const response = this.addDays(now, 3);
        const closure = this.addDays(now, 14);

        $("#targetResponse").val(this.formatDate(response));
        $("#targetClosure").val(this.formatDate(closure));

        const caseNo = "GRV-" + now.getFullYear() +
            String(now.getMonth() + 1).padStart(2, "0") + "-XXXX";

        $("#previewCaseNo").text(caseNo);
    },

    addDays(date, days) {
        const result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    },

    // Local-date formatter (avoids the UTC day-shift bug from toISOString())
    formatDate(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, "0");
        const d = String(date.getDate()).padStart(2, "0");
        return `${y}-${m}-${d}`;
    },

    datesAreValid() {
        const response = $("#targetResponse").val();
        const closure = $("#targetClosure").val();

        if (!response || !closure) return true; // let the `required` attribute handle empty fields

        return new Date(closure) >= new Date(response);
    },

    formatSize(bytes) {
        if (bytes >= 1024 * 1024) {
            return (bytes / (1024 * 1024)).toFixed(1) + " MB";
        }
        return (bytes / 1024).toFixed(1) + " KB";
    },

    /* ==========================================================
       Events
       ========================================================== */
    bind() {
        $("#formCase").on("submit", (e) => {
            e.preventDefault();

            if (!this.datesAreValid()) {
                Swal.fire({
                    icon: "warning",
                    title: "Invalid Dates",
                    text: "Target Closure Date cannot be earlier than Target Response Date."
                });
                return;
            }

            this.submit();
        });

        $("#btnDraft").on("click", () => {
            Swal.fire({
                icon: "info",
                title: "Coming Soon",
                text: "Save Draft feature is under development."
            });
        });

        $("#dropArea").on("click", () => $("#attachment").trigger("click"));

        $("#attachment").on("change", function () {
            NewCase.handleFiles(this.files);
        });

        $("#dropArea")
            .on("dragover", function (e) {
                e.preventDefault();
                $(this).addClass("drag");
            })
            .on("dragleave", function () {
                $(this).removeClass("drag");
            })
            .on("drop", function (e) {
                e.preventDefault();
                $(this).removeClass("drag");
                NewCase.handleFiles(e.originalEvent.dataTransfer.files);
            });

        // Remove a file from the pending attachment list
        $("#previewFiles").on("click", ".preview-remove", function () {
            const index = $(this).closest(".preview-file").data("index");
            NewCase.removeFile(index);
        });
    },

    /* ==========================================================
       Attachments
       ========================================================== */
    handleFiles(fileList) {
        const files = Array.from(fileList);
        const valid = [];
        const rejected = [];

        files.forEach(file => {
            const ext = file.name.split(".").pop().toLowerCase();
            const oversized = file.size > this.MAX_FILE_SIZE;
            const wrongType = !this.ALLOWED_EXT.includes(ext);

            if (oversized || wrongType) {
                rejected.push(file.name);
            } else {
                valid.push(file);
            }
        });

        this.setInputFiles(valid);
        this.renderPreview(valid);

        if (rejected.length) {
            Swal.fire({
                icon: "warning",
                title: "Some files were skipped",
                html: `Only JPG, PNG, PDF, DOC, DOCX up to 5 MB are allowed:<br><br>${rejected.join("<br>")}`
            });
        }
    },

    removeFile(index) {
        const current = Array.from($("#attachment")[0].files);
        current.splice(index, 1);
        this.setInputFiles(current);
        this.renderPreview(current);
    },

    // Sync a plain array of File objects back onto the <input type="file">
    setInputFiles(files) {
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        $("#attachment")[0].files = dataTransfer.files;
    },

    renderPreview(files) {
        if (!files.length) {
            $("#previewFiles").html("");
            return;
        }

        const rows = files.map((file, index) => {
            const icon = this.iconFor(file);
            return `
                <div class="preview-file" data-index="${index}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi ${icon} fs-5 text-primary"></i>
                        <div>
                            <strong>${file.name}</strong><br>
                            <small>${this.formatSize(file.size)}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-action preview-remove" title="Remove">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            `;
        });

        $("#previewFiles").html(rows.join(""));
    },

    iconFor(file) {
        if (file.type.includes("image")) return "bi-file-earmark-image";
        if (file.type.includes("pdf")) return "bi-file-earmark-pdf";
        if (/\.docx?$/i.test(file.name)) return "bi-file-earmark-word";
        return "bi-file-earmark";
    },

    /* ==========================================================
       Submit
       ========================================================== */
    submit() {
        const form = $("#formCase")[0];
        const formData = new FormData(form);

        $("#btnSubmit")
            .prop("disabled", true)
            .html('<i class="bi bi-hourglass-split"></i> Saving...');

        $.ajax({
            url: APP.baseUrl + "case/store",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: (response) => {
                if (!response.status) {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: response.message || "Failed to save case."
                    });
                    return;
                }

                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = APP.baseUrl + "case/case-detail/" + response.id;
                });
            },
            error: (xhr) => {
                if (xhr.status === 422) {
                    const html = $.map(xhr.responseJSON.errors, (value) => `<div class="text-start">• ${value}</div>`).join("");
                    Swal.fire({ icon: "warning", title: "Validation Error", html });
                } else {
                    Swal.fire({ icon: "error", title: "Server Error", text: "Unexpected server error." });
                }
            },
            complete: () => {
                $("#btnSubmit")
                    .prop("disabled", false)
                    .html('<i class="bi bi-send"></i> Submit Case');
            }
        });
    }
};

$(document).ready(() => NewCase.init());
(function () {

    if (window.__newCaseJsLoaded) return;
    window.__newCaseJsLoaded = true;

    const form       = document.getElementById('formCase');
    const tabs       = document.querySelectorAll('.wizard-tab');
    const panes      = document.querySelectorAll('.tab-pane');
    const dropArea   = document.getElementById('dropArea');
    const fileInput  = document.getElementById('attachment');
    const previewBox = document.getElementById('previewFiles');

    let selectedFiles = [];
    let currentStep   = 1;

    const MAX_SIZE = 5 * 1024 * 1024;
    const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

    // ---------- Tab navigation ----------
    function goToStep(step) {
        panes.forEach(p => p.classList.toggle('active', p.dataset.pane == step));

        tabs.forEach(t => {
            const s = Number(t.dataset.step);
            t.classList.toggle('active', s === step);
            t.classList.toggle('done', s < step);
        });

        currentStep = step;
        window.scrollTo({ top: form.offsetTop - 20, behavior: 'smooth' });

        if (step === 4) buildReview();
    }

    function clearErrors(pane) {
        pane.querySelectorAll('.form-group.has-error').forEach(g => g.classList.remove('has-error'));
    }

    function markError(input) {
        const group = input.closest('.form-group');
        if (group) group.classList.add('has-error');
    }

    function validateStep(step) {
        const pane = document.querySelector(`.tab-pane[data-pane="${step}"]`);
        clearErrors(pane);

        let valid = true;

        if (step === 1) {
            const site = form.querySelector('[name="site_id"]');
            if (site && site.hasAttribute('required') && ! site.value) {
                markError(site);
                valid = false;
            }
        }

        if (step === 2) {
            const message = form.querySelector('[name="message"]');
            if (message.value.trim().length < 10) {
                markError(message);
                valid = false;
            }

            const targetResponse = document.getElementById('targetResponse');
            const targetClosure  = document.getElementById('targetClosure');

            if (! targetResponse.value) {
                markError(targetResponse);
                valid = false;
            }

            if (! targetClosure.value) {
                markError(targetClosure);
                valid = false;
            } else if (targetResponse.value && targetClosure.value < targetResponse.value) {
                markError(targetClosure);
                valid = false;
            }
        }

        return valid;
    }

    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                goToStep(Number(btn.dataset.next));
            }
        });
    });

    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', () => goToStep(Number(btn.dataset.prev)));
    });

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = Number(tab.dataset.step);
            if (target <= currentStep) {
                goToStep(target); // boleh mundur bebas
            } else if (validateStep(currentStep)) {
                goToStep(target); // maju harus valid dulu
            }
        });
    });

    // ---------- File upload ----------
    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function renderFiles() {
        if (! selectedFiles.length) {
            previewBox.innerHTML = '';
            return;
        }

        previewBox.innerHTML = selectedFiles.map((f, i) => `
            <div class="file-chip">
                <i class="bi bi-file-earmark"></i>
                <span class="name">${f.name}</span>
                <span class="size">${formatSize(f.size)}</span>
                <button type="button" class="remove" data-index="${i}"><i class="bi bi-x-circle"></i></button>
            </div>
        `).join('');

        previewBox.querySelectorAll('.remove').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedFiles.splice(Number(btn.dataset.index), 1);
                syncFileInput();
                renderFiles();
            });
        });
    }

    function syncFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function addFiles(fileList) {
        const errors = [];

        Array.from(fileList).forEach(file => {
            const ext = file.name.split('.').pop().toLowerCase();

            if (! ALLOWED_EXT.includes(ext)) {
                errors.push(`${file.name}: tipe file tidak diizinkan.`);
                return;
            }

            if (file.size > MAX_SIZE) {
                errors.push(`${file.name}: ukuran melebihi 5 MB.`);
                return;
            }

            selectedFiles.push(file);
        });

        if (errors.length) {
            Swal.fire({ icon: 'warning', title: 'Beberapa file ditolak', html: errors.join('<br>') });
        }

        syncFileInput();
        renderFiles();
    }

    dropArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', e => addFiles(e.target.files));

    ['dragenter', 'dragover'].forEach(evt => {
        dropArea.addEventListener(evt, e => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        dropArea.addEventListener(evt, e => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
        });
    });

    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        addFiles(e.dataTransfer.files);
    });

    // ---------- Review ----------
    function labelOf(select) {
        if (! select) return '-';
        const opt = select.options[select.selectedIndex];
        return opt && opt.value ? opt.text : '-';
    }

    function buildReview() {
        const fd = new FormData(form);

        const siteEl = form.querySelector('[name="site_id"]');
        const siteLabel = siteEl.tagName === 'SELECT' ? labelOf(siteEl) : siteEl.closest('.form-group').querySelector('input[type="text"]').value;

        const info = [
            ['Site', siteLabel],
            ['Department', labelOf(form.querySelector('[name="department_id"]'))],
            ['Channel', labelOf(form.querySelector('[name="channel_id"]'))],
            ['Message Type', labelOf(form.querySelector('[name="message_type_id"]'))],
            ['Case Type', labelOf(form.querySelector('[name="case_type_id"]'))],
            ['Priority', labelOf(form.querySelector('[name="priority_id"]'))],
            ['Target Response', document.getElementById('targetResponse').value || '-'],
            ['Target Closure', document.getElementById('targetClosure').value || '-'],
            ['Confidential', document.getElementById('confidential').checked ? 'Yes' : 'No'],
            ['Repeated Case', document.getElementById('repeat').checked ? 'Yes' : 'No'],
        ];

        document.getElementById('reviewInfo').innerHTML = info.map(([k, v]) => `
            <div class="review-item">
                <small>${k}</small>
                <strong>${v}</strong>
            </div>
        `).join('');

        document.getElementById('reviewMessage').textContent = fd.get('message') || '-';

        document.getElementById('reviewAttachments').innerHTML = selectedFiles.length
            ? selectedFiles.map(f => `<div><i class="bi bi-paperclip"></i> ${f.name} (${formatSize(f.size)})</div>`).join('')
            : 'No files attached.';
    }

    // ---------- Submit ----------
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (! validateStep(1) || ! validateStep(2)) {
            Swal.fire({ icon: 'warning', title: 'Data belum lengkap', text: 'Periksa kembali Step 1 & 2.' });
            return;
        }

        const submitBtn = document.getElementById('btnSubmit');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Submitting...';

        const formData = new FormData(form);

        fetch(`${APP.baseUrl}case/store`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Case berhasil dibuat',
                        text: `Case number: ${data.case_number || ''}`,
                        confirmButtonText: 'OK',
                    }).then(() => {
                        window.location.href = `${APP.baseUrl}case/case-detail/${data.id}`;
                    });
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send"></i> Submit Case';

                    const errors = data.errors
                        ? Object.values(data.errors).flat().join('<br>')
                        : 'Terjadi kesalahan. Silakan coba lagi.';

                    Swal.fire({ icon: 'error', title: 'Gagal menyimpan case', html: errors });
                }
            })
            .catch(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send"></i> Submit Case';
                Swal.fire({ icon: 'error', title: 'Gagal terhubung ke server', text: 'Silakan coba lagi.' });
            });
    });

})();