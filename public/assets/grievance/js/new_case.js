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