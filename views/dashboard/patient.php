<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Patient Dashboard</h1>
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
        <!-- Stats Row -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo (int) $active; ?></h3>
                        <p>Active Appointments</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="index.php?page=appointments" class="small-box-footer">
                        View All <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo (int) $completed; ?></h3>
                        <p>Completed</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="index.php?page=appointments&status=completed" class="small-box-footer">
                        View History <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo (int) $prescriptionCount; ?></h3>
                        <p>Prescriptions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-prescription-bottle-alt"></i>
                    </div>
                    <a href="index.php?page=prescriptions" class="small-box-footer">
                        View All <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><i class="fas fa-plus"></i></h3>
                        <p>Book New</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <a href="index.php?page=appointments&action=book" class="small-box-footer">
                        Book Appointment <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Next Appointment -->
        <?php if ($nextAppointment): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-day mr-1"></i>
                            Next Appointment
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-light">
                                <?php echo htmlspecialchars(formatDate($nextAppointment['appt_date']), ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <i class="fas fa-user-md fa-3x text-primary mb-2"></i>
                                <h5>Doctor</h5>
                                <p class="text-muted"><?php echo htmlspecialchars($nextAppointment['doctor_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-stethoscope fa-3x text-info mb-2"></i>
                                <h5>Specialization</h5>
                                <p class="text-muted"><?php echo htmlspecialchars($nextAppointment['specialization'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                                <h5>Time</h5>
                                <p class="text-muted"><?php echo htmlspecialchars(formatTime($nextAppointment['appt_time']), ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="index.php?page=appointments&action=view&id=<?php echo $nextAppointment['id']; ?>" class="btn btn-info">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="index.php?page=appointments&action=cancel" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                <i class="fas fa-times"></i> Cancel Appointment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-1"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="index.php?page=appointments&action=book" class="btn btn-block btn-primary">
                                    <i class="fas fa-calendar-plus mr-2"></i> Book Appointment
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="index.php?page=appointments" class="btn btn-block btn-info">
                                    <i class="fas fa-calendar-check mr-2"></i> My Appointments
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="index.php?page=prescriptions" class="btn btn-block btn-success">
                                    <i class="fas fa-prescription-bottle mr-2"></i> Prescriptions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
