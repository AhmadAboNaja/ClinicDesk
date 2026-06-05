<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-prescription mr-2"></i>Add Prescription</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=appointments">Appointments</a></li>
                    <li class="breadcrumb-item active">Add Prescription</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php displayFlash(); ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prescription Information</h3>
                    </div>
                    <form method="POST" action="index.php?page=prescriptions&action=add&appointment_id=<?php echo $appointmentId; ?>" enctype="multipart/form-data">
                        <div class="card-body">
                            <?php echo CSRF::input(); ?>
                            
                            <!-- Appointment Info -->
                            <?php if (!empty($appointment)): ?>
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle mr-1"></i> Appointment Details</h5>
                                <p class="mb-0">
                                    <strong>Patient:</strong> <?php echo htmlspecialchars($appointment['patient_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?><br>
                                    <strong>Date:</strong> <?php echo htmlspecialchars(formatDate($appointment['appt_date']), ENT_QUOTES, 'UTF-8'); ?><br>
                                    <strong>Time:</strong> <?php echo htmlspecialchars(formatTime($appointment['appt_time']), ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="diagnosis">Diagnosis <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="diagnosis" name="diagnosis" rows="4" required placeholder="Enter diagnosis details..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="medications">Medications <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="medications" name="medications" rows="4" required placeholder="Enter medications with dosage and frequency..."></textarea>
                                <small class="form-text text-muted">Include medication name, dosage, frequency, and duration.</small>
                            </div>
                            <div class="form-group">
                                <label for="notes">Additional Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional instructions or notes..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="prescription_file">Prescription File (Optional)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="prescription_file" name="prescription_file" accept="application/pdf">
                                    <label class="custom-file-label" for="prescription_file">Choose PDF file</label>
                                </div>
                                <small class="form-text text-muted">PDF format only, maximum 3MB.</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Prescription
                            </button>
                            <a href="index.php?page=appointments" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i>Important</h3>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>Ensure all medication details are accurate.</li>
                            <li>Include dosage instructions clearly.</li>
                            <li>Specify the duration of treatment.</li>
                            <li>Add any special instructions for the patient.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<script>
// File input label update
document.getElementById('prescription_file').addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name || 'Choose PDF file';
    document.querySelector('.custom-file-label').textContent = fileName;
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
