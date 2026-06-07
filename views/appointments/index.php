<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-calendar-alt mr-2"></i>Appointments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Appointments</li>
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
                        <h3 class="card-title">Appointments List</h3>
                        <div class="card-tools">
                            <?php if (Auth::role() === 'patient'): ?>
                            <a href="index.php?page=appointments&action=book" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i> Book Appointment
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <?php if (Auth::role() === 'admin' || Auth::role() === 'patient'): ?>
                                            <th>Doctor</th>
                                            <th>Specialization</th>
                                        <?php endif; ?>
                                        <?php if (Auth::role() === 'admin' || Auth::role() === 'doctor'): ?>
                                            <th>Patient</th>
                                        <?php endif; ?>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($appointments)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
                                                No appointments found.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($appointments as $appt): ?>
                                        <tr>
                                            <?php if (Auth::role() === 'admin' || Auth::role() === 'patient'): ?>
                                                <td><?php echo htmlspecialchars($appt['doctor_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($appt['specialization'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <?php endif; ?>
                                            <?php if (Auth::role() === 'admin' || Auth::role() === 'doctor'): ?>
                                                <td><?php echo htmlspecialchars($appt['patient_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <?php endif; ?>
                                            <td><?php echo htmlspecialchars(formatDate($appt['appt_date']), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars(formatTime($appt['appt_time']), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($appt['reason'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = match($appt['status']) {
                                                    'pending' => 'warning',
                                                    'confirmed' => 'info',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary'
                                                };
                                                ?>
                                                <span class="badge badge-<?php echo $statusClass; ?>">
                                                    <?php echo htmlspecialchars(ucfirst($appt['status']), ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="index.php?page=appointments&action=view&id=<?php echo $appt['id']; ?>" class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if (Auth::role() === 'patient' && $appt['status'] === 'pending'): ?>
                                                    -<form method="POST" action="index.php?page=appointments&action=cancel&id=<?php echo $appt['id']; ?>" style="display:inline;">
                                                        <?php echo CSRF::input(); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Cancel" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                    <?php if ((Auth::role() === 'doctor' || Auth::role() === 'admin') && $appt['status'] === 'pending'): ?>
                                                    <form method="POST" action="index.php?page=appointments&action=updateStatus&id=<?php echo $appt['id']; ?>" style="display:inline;">
                                                        <?php echo CSRF::input(); ?>
                                                        <input type="hidden" name="id" value="<?php echo $appt['id']; ?>">
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Confirm">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                    <?php if ((Auth::role() === 'doctor') && in_array($appt['status'], ['confirmed', 'pending'])): ?>
                                                   - <a href="index.php?page=prescriptions&action=add&appointment_id=<?php echo $appt['id']; ?>" class="btn btn-sm btn-warning" title="Add Prescription">
                                                        <i class="fas fa-prescription"></i>
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
