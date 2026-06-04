<?php require_once __DIR__ . '/../partials/header.php'; ?>
<section class="content">
    <div class="content-header">
        <h1>Add Prescription</h1>
    </div>
    <?php displayFlash(); ?>
    <div class="card">
        <form method="POST" action="index.php?page=prescriptions&action=add&id=<?php echo $appointmentId; ?>" enctype="multipart/form-data">
            <div class="card-body">
                <?php echo CSRF::input(); ?>
                <div class="form-group">
                    <label for="diagnosis">Diagnosis *</label>
                    <textarea id="diagnosis" name="diagnosis" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="medications">Medications *</label>
                    <textarea id="medications" name="medications" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="prescription_file">Prescription File (PDF, max 3MB)</label>
                    <input type="file" id="prescription_file" name="prescription_file" accept="application/pdf">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn">Save Prescription</button>
                <a href="index.php?page=appointments" class="btn" style="background:#6c757d;">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
