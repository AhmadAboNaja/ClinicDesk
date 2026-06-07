<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-prescription-bottle-alt mr-2"></i>My Prescriptions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Prescriptions</li>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prescriptions List</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="mb-3">
                            <input type="hidden" name="page" value="prescriptions">

                            <div class="row">

                                <?php if (Auth::role() === 'admin'): ?>
                                    <div class="col-md-3">
                                        <label>Doctor</label>
                                        <input type="text"
                                            name="doctor_name"
                                            class="form-control"
                                            value="<?= htmlspecialchars($_GET['doctor_name'] ?? '') ?>">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Patient</label>
                                        <input type="text"
                                            name="patient_name"
                                            class="form-control"
                                            value="<?= htmlspecialchars($_GET['patient_name'] ?? '') ?>">
                                    </div>
                                <?php endif; ?>

                                <div class="col-md-2">
                                    <label>From</label>
                                    <input type="date"
                                        name="date_from"
                                        class="form-control"
                                        value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
                                </div>

                                <div class="col-md-2">
                                    <label>To</label>
                                    <input type="date"
                                        name="date_to"
                                        class="form-control"
                                        value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary mr-2">
                                        <i class="fas fa-search"></i> Filter
                                    </button>

                                    <a href="index.php?page=prescriptions"
                                        class="btn btn-secondary">
                                        Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Diagnosis</th>
                                        <th>Medications</th>
                                        <th>File</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($prescriptions)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="fas fa-prescription-bottle fa-2x mb-2"></i><br>
                                                No prescriptions found.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($prescriptions as $presc): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($presc['doctor_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($presc['patient_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(formatDate($presc['appt_date']), ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars(substr($presc['diagnosis'] ?? '', 0, 50), ENT_QUOTES, 'UTF-8'); ?><?php echo strlen($presc['diagnosis'] ?? '') > 50 ? '...' : ''; ?></td>
                                                <td><?php echo htmlspecialchars(substr($presc['medications'] ?? '', 0, 50), ENT_QUOTES, 'UTF-8'); ?><?php echo strlen($presc['medications'] ?? '') > 50 ? '...' : ''; ?></td>
                                                <td>
                                                    <?php if (!empty($presc['file_path'])): ?>
                                                        <span class="badge badge-success"><i class="fas fa-file-pdf mr-1"></i> Available</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">No File</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="index.php?page=prescriptions&action=view&id=<?php echo $presc['id']; ?>" class="btn btn-sm btn-info" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if (!empty($presc['file_path'])): ?>
                                                            <a href="index.php?page=prescriptions&action=download&id=<?php echo $presc['id']; ?>" class="btn btn-sm btn-success" title="Download">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <?php if (isset($paginator)): ?>
                            <?php echo $paginator->render(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>