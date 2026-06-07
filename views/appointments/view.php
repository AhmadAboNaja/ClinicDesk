<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Appointment Details
                </h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Appointment #<?php echo $appointment['id']; ?>
            </h3>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="info-box">
                        <span class="info-box-icon bg-primary">
                            <i class="fas fa-user-md"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Doctor</span>
                            <span class="info-box-number">
                                <?php echo htmlspecialchars($appointment['doctor_name']); ?>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-user"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">Patient</span>
                            <span class="info-box-number">
                                <?php echo htmlspecialchars($appointment['patient_name']); ?>
                            </span>
                        </div>
                    </div>

                </div>

            </div>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Specialization</th>
                    <td>
                        <?php echo htmlspecialchars($appointment['specialization']); ?>
                    </td>
                </tr>

                <tr>
                    <th>Date</th>
                    <td>
                        <?php echo formatDate($appointment['appt_date']); ?>
                    </td>
                </tr>

                <tr>
                    <th>Time</th>
                    <td>
                        <?php echo formatTime($appointment['appt_time']); ?>
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>

                        <?php
                        $statusClass = match($appointment['status']) {
                            'pending' => 'warning',
                            'confirmed' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            default => 'secondary'
                        };
                        ?>

                        <span class="badge badge-<?php echo $statusClass; ?>">
                            <?php echo ucfirst($appointment['status']); ?>
                        </span>

                    </td>
                </tr>

                <tr>
                    <th>Reason</th>
                    <td>
                        <?php echo nl2br(htmlspecialchars($appointment['reason'])); ?>
                    </td>
                </tr>

                <tr>
                    <th>Patient Email</th>
                    <td>
                        <?php echo htmlspecialchars($appointment['patient_email']); ?>
                    </td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>
                        <?php echo formatDate($appointment['created_at']); ?>
                    </td>
                </tr>

            </table>

        </div>

        <div class="card-footer">
            <a href="index.php?page=appointments"
               class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i>
                Back
            </a>

            <?php if ((Auth::role() === 'doctor' || Auth::role() === 'admin') && $appointment['status'] === 'confirmed'): ?>
                <a href="index.php?page=prescriptions&action=add&id=<?php echo $appointment['id']; ?>"
                   class="btn btn-warning">
                    <i class="fas fa-prescription mr-1"></i>
                    Add Prescription
                </a>
            <?php endif; ?>
        </div>

    </div>

</div>
</section>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>