<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-prescription mr-2"></i>Prescription Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php?page=prescriptions">Prescriptions</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">
                            <i class="fas fa-file-medical mr-2"></i>
                            Prescription #<?php echo (int) $prescription['id']; ?>
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-light">
                                <?php echo htmlspecialchars(formatDate($prescription['appt_date'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Patient & Doctor Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-box-content">
                                        <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Patient</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($prescription['patient_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-box-content">
                                        <span class="info-box-icon bg-success"><i class="fas fa-user-md"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Doctor</span>
                                            <span class="info-box-number"><?php echo htmlspecialchars($prescription['doctor_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diagnosis -->
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-stethoscope mr-1"></i> Diagnosis</h3>
                            </div>
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($prescription['diagnosis'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
                            </div>
                        </div>

                        <!-- Medications -->
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-pills mr-1"></i> Medications</h3>
                            </div>
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($prescription['medications'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
                            </div>
                        </div>

                        <!-- Notes -->
                        <?php if (!empty($prescription['notes'])): ?>
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-sticky-note mr-1"></i> Additional Notes</h3>
                            </div>
                            <div class="card-body">
                                <p><?php echo nl2br(htmlspecialchars($prescription['notes'], ENT_QUOTES, 'UTF-8')); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Attachment -->
                        <?php if (!empty($prescription['file_path'])): ?>
                        <div class="card card-outline card-danger">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-paperclip mr-1"></i> Attachment</h3>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <br>
                                    <a href="index.php?page=prescriptions&action=download&id=<?php echo $prescription['id']; ?>" class="btn btn-danger">
                                        <i class="fas fa-download mr-1"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="index.php?page=prescriptions" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Prescriptions
                        </a>
                        <?php if (!empty($prescription['file_path'])): ?>
                        <a href="index.php?page=prescriptions&action=download&id=<?php echo $prescription['id']; ?>" class="btn btn-success float-right">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
