<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Admin Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Stats Cards -->
        <?php if (!empty($totalByRole)): ?>
        <div class="row">
            <?php foreach ($totalByRole as $roleStat): ?>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-<?= $roleStat['role'] === 'admin' ? 'danger' : ($roleStat['role'] === 'doctor' ? 'success' : 'info') ?>">
                    <div class="inner">
                        <h3><?php echo (int) $roleStat['total']; ?></h3>
                        <p><?php echo htmlspecialchars(ucfirst($roleStat['role']), ENT_QUOTES, 'UTF-8'); ?>s</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-<?= $roleStat['role'] === 'admin' ? 'user-shield' : ($roleStat['role'] === 'doctor' ? 'user-md' : 'users') ?>"></i>
                    </div>
                    <a href="index.php?page=<?= $roleStat['role'] === 'doctor' ? 'doctors' : 'users' ?>" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Today's Appointments -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-day mr-1"></i>
                            Today's Appointments
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info"><?php echo (int) $todayAppointments; ?> Total</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($todayAppointmentsList)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($todayAppointmentsList as $appt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(formatTime($appt['appt_time']), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($appt['patient_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($appt['doctor_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <span class="badge badge-<?= $appt['status'] === 'completed' ? 'success' : ($appt['status'] === 'cancelled' ? 'danger' : 'warning') ?>">
                                                <?php echo htmlspecialchars(ucfirst($appt['status']), ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?page=appointments&action=view&id=<?php echo $appt['id']; ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>No appointments scheduled for today.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
